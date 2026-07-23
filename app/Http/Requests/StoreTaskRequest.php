<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'audit_point_id' => 'required|exists:audit_points,id',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_manager' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ];
    }
}
