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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,normal,high'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'due_date' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'attachment' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'],
        ];
    }
}
