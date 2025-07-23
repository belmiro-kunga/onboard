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
            'category' => 'nullable|string|max:100',
            'content_type' => ['required', Rule::in(['video', 'text', 'quiz', 'mixed'])],
            'duration_minutes' => 'required|integer|min:1',
            'estimated_duration' => 'nullable|integer|min:1',
            'difficulty_level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'course_id' => 'nullable|exists:courses,id',
            'points_reward' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:modules,id',
            'requirements' => 'nullable|array',
            'content_data' => 'nullable|array',
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
