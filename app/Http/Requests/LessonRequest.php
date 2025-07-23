<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_optional' => 'boolean',
            'duration_minutes' => 'nullable|integer|min:1',
            
            // Validações para vídeo
            'video_type' => 'nullable|in:youtube,local,vimeo',
            'video_url' => 'required_if:video_type,youtube,vimeo|nullable|url',
            'video_file' => 'required_if:video_type,local|nullable|file|mimes:mp4,avi,mov,wmv|max:1048576', // 1GB
            'auto_play_next' => 'boolean',
            'picture_in_picture' => 'boolean',
            
            // Validações para materiais
            'materials' => 'nullable|array',
            'materials.*.title' => 'required_with:materials.*|string|max:255',
            'materials.*.description' => 'nullable|string',
            'materials.*.type' => 'required_with:materials.*|in:pdf,doc,docx,slide,link,image,video,audio',
            'materials.*.external_url' => 'required_if:materials.*.type,link|nullable|url',
            'materials.*.file' => 'nullable|file|max:51200', // 50MB
            'materials.*.is_downloadable' => 'boolean',
            
            // Validações para quiz
            'quiz_title' => 'nullable|string|max:255',
            'quiz_description' => 'nullable|string',
            'quiz_type' => 'nullable|in:quiz,reflection,activity',
            'quiz_questions' => 'nullable|array',
            'quiz_time_limit' => 'nullable|integer|min:1',
            'quiz_max_attempts' => 'nullable|integer|min:1',
            'quiz_passing_score' => 'nullable|numeric|min:0|max:100',
            'quiz_is_required' => 'boolean',
            'quiz_show_results' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'module_id.required' => 'O módulo é obrigatório.',
            'module_id.exists' => 'O módulo selecionado não existe.',
            'title.required' => 'O título da aula é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'duration_minutes.min' => 'A duração deve ser de pelo menos 1 minuto.',
            
            'video_type.in' => 'Tipo de vídeo inválido.',
            'video_url.required_if' => 'A URL do vídeo é obrigatória para este tipo.',
            'video_url.url' => 'A URL do vídeo deve ser válida.',
            'video_file.required_if' => 'O arquivo de vídeo é obrigatório para vídeos locais.',
            'video_file.mimes' => 'O vídeo deve ser um arquivo MP4, AVI, MOV ou WMV.',
            'video_file.max' => 'O arquivo de vídeo não pode ser maior que 1GB.',
            
            'materials.*.title.required_with' => 'O título do material é obrigatório.',
            'materials.*.type.required_with' => 'O tipo do material é obrigatório.',
            'materials.*.type.in' => 'Tipo de material inválido.',
            'materials.*.external_url.required_if' => 'A URL é obrigatória para links externos.',
            'materials.*.external_url.url' => 'A URL deve ser válida.',
            'materials.*.file.max' => 'O arquivo não pode ser maior que 50MB.',
            
            'quiz_time_limit.min' => 'O tempo limite deve ser de pelo menos 1 minuto.',
            'quiz_max_attempts.min' => 'O número máximo de tentativas deve ser pelo menos 1.',
            'quiz_passing_score.min' => 'A nota mínima deve ser pelo menos 0.',
            'quiz_passing_score.max' => 'A nota mínima não pode ser maior que 100.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Converter checkboxes para boolean
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'is_optional' => $this->boolean('is_optional', false),
            'auto_play_next' => $this->boolean('auto_play_next', false),
            'picture_in_picture' => $this->boolean('picture_in_picture', true),
            'quiz_is_required' => $this->boolean('quiz_is_required', false),
            'quiz_show_results' => $this->boolean('quiz_show_results', true),
        ]);

        // Processar materiais
        if ($this->has('materials')) {
            $materials = $this->input('materials', []);
            foreach ($materials as $index => $material) {
                $materials[$index]['is_downloadable'] = $this->boolean("materials.{$index}.is_downloadable", true);
            }
            $this->merge(['materials' => $materials]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'module_id' => 'módulo',
            'title' => 'título',
            'description' => 'descrição',
            'objective' => 'objetivo',
            'order_index' => 'ordem',
            'is_active' => 'ativo',
            'is_optional' => 'opcional',
            'duration_minutes' => 'duração',
            'video_type' => 'tipo de vídeo',
            'video_url' => 'URL do vídeo',
            'video_file' => 'arquivo de vídeo',
            'auto_play_next' => 'reprodução automática',
            'picture_in_picture' => 'picture-in-picture',
            'quiz_title' => 'título do quiz',
            'quiz_description' => 'descrição do quiz',
            'quiz_type' => 'tipo do quiz',
            'quiz_time_limit' => 'tempo limite',
            'quiz_max_attempts' => 'máximo de tentativas',
            'quiz_passing_score' => 'nota mínima',
            'quiz_is_required' => 'quiz obrigatório',
            'quiz_show_results' => 'mostrar resultados',
        ];
    }
}