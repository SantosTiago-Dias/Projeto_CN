<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskEditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'priority'=>['sometimes','in:HIGH,LOW,MEDIUM'],
            'status' => 'sometimes|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'due_date' => ['sometimes', Rule::date()->afterOrEqual(today())],
            'worker_id' => ['sometimes', 'exists:users,id'],
        ];
    }
}
