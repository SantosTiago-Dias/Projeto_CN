<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'priority'=>['required','in:HIGH,LOW,MEDIUM'],
            'status' => 'required|in:PENDING,IN_PROGRESS',
            'due_date' => ['sometimes', Rule::date()->afterOrEqual(today())],
            'outside' => ['required','boolean'],
            'worker_id' => ['required', 'exists:users,id'],
        ];
    }
}
