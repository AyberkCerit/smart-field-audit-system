<?php

namespace App\Actions\Tasks;

use App\Models\AuditPoint;
use App\Models\Task;
use App\Events\TaskCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateTaskAction
{
    /**
     * @param array $data Validated task data
     * @param Request $request Original request for media library
     * @return Task
     */
    public function execute(array $data, Request $request): Task
    {
        $task = null;

        DB::transaction(function () use ($data, $request, &$task) {
            $auditPoint = AuditPoint::create([
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
            $data['status'] = \App\Enums\TaskStatus::PENDING->value;
            
            $task = Task::create($data);
            
            if ($request->hasFile('attachment')) {
                // Upload to S3 (MinIO) using Spatie MediaLibrary
                $task->addMediaFromRequest('attachment')->toMediaCollection('task_attachments', 's3');
            }
        });

        if ($task) {
            event(new TaskCreated($task));
        }

        return $task;
    }
}
