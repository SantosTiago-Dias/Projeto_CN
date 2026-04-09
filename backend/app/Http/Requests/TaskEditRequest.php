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
            'outside' => ['sometimes','boolean'],
            'due_date' => ['sometimes', Rule::date()->afterOrEqual(today())],
            'worker_id' => ['sometimes', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string'             => 'O título deve ser um texto.',
            'description.string'       => 'A descrição deve ser um texto.',
            'priority.in'              => 'A prioridade deve ser: HIGH, LOW ou MEDIUM.',
            'status.in'                => 'O status deve ser: PENDING, IN_PROGRESS, COMPLETED ou CANCELLED.',
            'outside.boolean'          => 'O campo outside deve ser verdadeiro ou falso.',
            'due_date.date'            => 'A data de vencimento deve ser uma data válida.',
            'due_date.after_or_equal'  => 'A data de vencimento deve ser hoje ou uma data futura.',
            'worker_id.exists'         => 'O trabalhador selecionado não existe.',
        ];
    }
}
