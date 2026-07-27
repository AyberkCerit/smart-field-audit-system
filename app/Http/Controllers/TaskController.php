<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['auditPoint', 'assignedUser', 'manager'])->paginate(15);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        $auditPoint = \App\Models\AuditPoint::create([
            'name' => $data['title'] . ' (Görev Alanı)',
            'description' => 'Haritadan görev oluşturulurken otomatik eklendi.',
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
            // Spatie MediaLibrary kullanarak S3 (MinIO) sunucusuna yükle
            $task->addMediaFromRequest('attachment')->toMediaCollection('task_attachments', 's3');
        }
        
        return redirect()->route('tasks.index')->with('success', 'Görev başarıyla oluşturuldu.');
    }

    public function show(Task $task)
    {
        // $task->load(['auditPoint', 'assignedUser', 'manager']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
