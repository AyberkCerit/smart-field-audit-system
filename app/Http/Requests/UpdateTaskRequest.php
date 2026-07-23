<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|string',
            'priority' => 'sometimes|required|string',
            'audit_point_id' => 'sometimes|required|exists:audit_points,id',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_manager' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ];
    }
}
