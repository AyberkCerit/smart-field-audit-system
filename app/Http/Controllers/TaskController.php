<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Task::with(['auditPoint', 'assignedUser', 'manager']);

        // Eğer kullanıcı sadece field_personnel ise, sadece havuzdakileri ve kendine atananları görsün.
        // Admin ve Manager her şeyi görebilir.
        if (auth()->user()->hasRole('field_personnel') && !auth()->user()->hasAnyRole(['admin', 'manager'])) {
            $query->where(function ($q) {
                $q->where('assigned_to', auth()->id())
                  ->orWhereNull('assigned_to');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->paginate(15)->withQueryString();
        
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        // Only fetch users with 'field_personnel' role
        $users = \App\Models\User::role('field_personnel')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = null;

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $request, &$task) {
            $auditPoint = \App\Models\AuditPoint::create([
                'name' => $data['title'] . ' (Task Area)',
                'description' => 'Automatically added when creating a task from the map.',
                'category' => 'task_specific',
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'is_active' => true,
            ]);

            unset($data['latitude'], $data['longitude']);

            $data['audit_point_id'] = $auditPoint->id;
            $data['assigned_manager'] = auth()->id();
            $data['status'] = 'pending';
            $task = Task::create($data);
            
            if ($request->hasFile('attachment')) {
                // Upload to S3 (MinIO) using Spatie MediaLibrary
                $task->addMediaFromRequest('attachment')->toMediaCollection('task_attachments', 's3');
            }
        });

        // Bildirimler
        if ($task) {
            event(new \App\Events\TaskCreated($task));
        }
        
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['auditPoint', 'assignedUser', 'manager']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = \App\Models\User::role('field_personnel')->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $task) {
            // Update AuditPoint if title, lat, or lng are changed
            if ($task->auditPoint) {
                $auditData = [];
                if (isset($data['title'])) {
                    $auditData['name'] = $data['title'] . ' (Görev Alanı)';
                }
                if (isset($data['latitude'])) {
                    $auditData['latitude'] = $data['latitude'];
                }
                if (isset($data['longitude'])) {
                    $auditData['longitude'] = $data['longitude'];
                }
                
                if (!empty($auditData)) {
                    $task->auditPoint->update($auditData);
                }
            }
            
            // Remove lat/lng before updating Task
            unset($data['latitude'], $data['longitude']);
            
            $task->update($data);
        });

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function updateStatus(\Illuminate\Http\Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $task->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            event(new \App\Events\TaskCompleted($task));
        }

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    public function destroy(Task $task)
    {
        if ($task->auditPoint) {
            $task->auditPoint->delete();
        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function attachment(Task $task, \Spatie\MediaLibrary\MediaCollections\Models\Media $media)
    {
        $this->authorize('view', $task);
        abort_if($media->model_id !== $task->id, 403);
        
        return response()->stream(function () use ($media) {
            $stream = $media->stream();
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => 'inline; filename="'.$media->file_name.'"',
        ]);
    }

    public function claimTask(\Illuminate\Http\Request $request, Task $task)
    {
        $this->authorize('claim', $task);

        $task->update(['assigned_to' => auth()->id()]);
        return redirect()->back()->with('success', 'Görevi başarıyla devraldınız.');
    }

    public function completeTask(\Illuminate\Http\Request $request, Task $task)
    {
        $this->authorize('complete', $task);

        $request->validate([
            'proof_photo' => 'required|image|max:10240', // 10MB
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Mesafe hesaplama (Haversine formülü)
        if ($task->auditPoint) {
            $lat1 = $request->latitude;
            $lon1 = $request->longitude;
            $lat2 = $task->auditPoint->latitude;
            $lon2 = $task->auditPoint->longitude;

            $earthRadius = 6371000; // meters
            $latDelta = deg2rad($lat2 - $lat1);
            $lonDelta = deg2rad($lon2 - $lon1);
            $a = sin($latDelta / 2) * sin($latDelta / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            $maxDistance = config('app.task_completion_radius', 200);

            if ($distance > $maxDistance) {
                return redirect()->back()->with('error', 'Görev konumuna çok uzaksınız. (Mesafe: ' . round($distance) . 'm. Maksimum ' . $maxDistance . 'm olmalıdır.)');
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $task) {
            $task->update(['status' => 'completed']);
            
            if ($task->assigned_to === null) {
                $task->update(['assigned_to' => auth()->id()]);
            }

            $task->addMediaFromRequest('proof_photo')->toMediaCollection('task_proofs', 's3');
        });

        event(new \App\Events\TaskCompleted($task));

        return redirect()->back()->with('success', 'Görev başarıyla tamamlandı.');
    }
}
