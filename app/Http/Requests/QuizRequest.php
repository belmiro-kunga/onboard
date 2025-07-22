<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizRequest extends FormRequest
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
            'time_limit' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'difficulty_level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título do quiz é obrigatório.',
            'description.required' => 'A descrição do quiz é obrigatória.',
            'passing_score.required' => 'A nota mínima é obrigatória.',
            'difficulty_level.required' => 'O nível de dificuldade é obrigatório.',
        ];
    }
}
