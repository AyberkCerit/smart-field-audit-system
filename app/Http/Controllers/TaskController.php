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

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $request) {
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
        
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['auditPoint', 'assignedUser', 'manager']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
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
}
