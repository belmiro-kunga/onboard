<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $courseId = $this->route('course') ? $this->route('course')->id : null;

        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_hours' => 'nullable|integer|min:0',
            'difficulty_level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'type' => ['required', Rule::in(['mandatory', 'optional', 'certification'])],
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título do curso é obrigatório.',
            'description.required' => 'A descrição do curso é obrigatória.',
            'difficulty_level.required' => 'O nível de dificuldade é obrigatório.',
            'type.required' => 'O tipo do curso é obrigatório.',
            'thumbnail.image' => 'O arquivo deve ser uma imagem válida.',
            'thumbnail.max' => 'A imagem não pode ser maior que 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Processar tags
        if ($this->has('tags') && $this->tags) {
            $tags = array_map('trim', explode(',', $this->tags));
            $this->merge([
                'tags' => array_filter($tags)
            ]);
        }
    }
}
