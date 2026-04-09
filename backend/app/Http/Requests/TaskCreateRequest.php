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

    public function messages(): array
    {
        return [
            'title.required'           => 'O título é obrigatório.',
            'title.string'             => 'O título deve ser um texto.',
            'description.required'     => 'A descrição é obrigatória.',
            'description.string'       => 'A descrição deve ser um texto.',
            'priority.required'        => 'A prioridade é obrigatória.',
            'priority.in'              => 'A prioridade deve ser: HIGH, LOW ou MEDIUM.',
            'status.required'          => 'O status é obrigatório.',
            'status.in'                => 'O status deve ser: PENDING ou IN_PROGRESS.',
            'due_date.date'            => 'A data de vencimento deve ser uma data válida.',
            'due_date.after_or_equal'  => 'A data de vencimento deve ser hoje ou uma data futura.',
            'outside.required'         => 'O campo outside é obrigatório.',
            'outside.boolean'          => 'O campo outside deve ser verdadeiro ou falso.',
            'worker_id.required'       => 'O trabalhador é obrigatório.',
            'worker_id.exists'         => 'O trabalhador selecionado não existe.',
        ];
    }
}
