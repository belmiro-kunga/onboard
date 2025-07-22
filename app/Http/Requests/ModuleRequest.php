<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content_type' => ['required', Rule::in(['video', 'text', 'quiz', 'mixed'])],
            'duration_minutes' => 'required|integer|min:1',
            'difficulty_level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'course_id' => 'nullable|exists:courses,id',
            'thumbnail' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título do módulo é obrigatório.',
            'description.required' => 'A descrição do módulo é obrigatória.',
            'content_type.required' => 'O tipo de conteúdo é obrigatório.',
            'duration_minutes.required' => 'A duração é obrigatória.',
            'difficulty_level.required' => 'O nível de dificuldade é obrigatório.',
        ];
    }
}
