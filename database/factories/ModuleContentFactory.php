<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModuleContent>
 */
class ModuleContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleContent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contentTypes = ['video', 'text', 'interactive', 'document', 'audio'];
        $contentType = $this->faker->randomElement($contentTypes);

        return [
            'module_id' => Module::factory(),
            'title' => $this->faker->sentence(3),
            'content_type' => $contentType,
            'content_data' => $this->getContentDataForType($contentType),
            'order_index' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean(90), // 90% chance de ser ativo
            'duration' => $this->faker->numberBetween(60, 1800), // 1-30 minutos em segundos
            'file_path' => $this->faker->optional(0.7)->filePath(),
            'file_size' => $this->faker->optional(0.7)->numberBetween(1024, 10485760), // 1KB-10MB
            'mime_type' => $this->getMimeTypeForContentType($contentType),
            'transcript' => $this->faker->optional(0.3)->randomElements([
                ['start' => 0, 'end' => 30, 'text' => 'Bem-vindo ao módulo de introdução.'],
                ['start' => 30, 'end' => 60, 'text' => 'Hoje vamos aprender sobre nossa cultura empresarial.'],
                ['start' => 60, 'end' => 90, 'text' => 'A HCP valoriza a inovação e o trabalho em equipe.'],
            ], $this->faker->numberBetween(1, 3)),
            'interactive_markers' => $this->faker->optional(0.4)->randomElements([
                ['time' => 30, 'title' => 'Ponto importante', 'content' => 'Informação adicional'],
                ['time' => 60, 'title' => 'Exemplo prático', 'content' => 'Demonstração do conceito'],
                ['time' => 90, 'title' => 'Resumo', 'content' => 'Principais pontos abordados'],
            ], $this->faker->numberBetween(1, 3)),
            'notes_enabled' => $this->faker->boolean(80),
            'bookmarks_enabled' => $this->faker->boolean(70),
        ];
    }

    /**
     * Indicate that the content is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the content is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the content is video type.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'content_type' => 'video',
            'content_data' => [
                'video_url' => $this->faker->url(),
                'transcript' => $this->faker->paragraphs(3, true),
                'markers' => [
                    ['time' => 30, 'title' => 'Introdução'],
                    ['time' => 120, 'title' => 'Conceitos principais'],
                    ['time' => 300, 'title' => 'Conclusão'],
                ]
            ],
            'mime_type' => 'video/mp4',
            'duration' => $this->faker->numberBetween(300, 1800), // 5-30 minutos
        ]);
    }

    /**
     * Indicate that the content is text type.
     */
    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'content_type' => 'text',
            'content_data' => [
                'html_content' => $this->faker->paragraphs(5, true),
                'sections' => [
                    ['title' => 'Introdução', 'content' => $this->faker->paragraph()],
                    ['title' => 'Desenvolvimento', 'content' => $this->faker->paragraphs(2, true)],
                    ['title' => 'Conclusão', 'content' => $this->faker->paragraph()],
                ]
            ],
            'mime_type' => 'text/html',
            'duration' => $this->faker->numberBetween(60, 300), // 1-5 minutos
        ]);
    }

    /**
     * Indicate that the content is interactive type.
     */
    public function interactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'content_type' => 'interactive',
            'content_data' => [
                'scenarios' => [
                    ['id' => 1, 'title' => 'Cenário 1', 'description' => 'Descrição do cenário'],
                    ['id' => 2, 'title' => 'Cenário 2', 'description' => 'Descrição do cenário'],
                ],
                'interactions' => [
                    ['type' => 'quiz', 'question' => 'Pergunta interativa'],
                    ['type' => 'choice', 'options' => ['Opção A', 'Opção B', 'Opção C']],
                ]
            ],
            'mime_type' => 'application/json',
            'duration' => $this->faker->numberBetween(180, 600), // 3-10 minutos
        ]);
    }

    /**
     * Indicate that the content is document type.
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'content_type' => 'document',
            'content_data' => [
                'document_url' => $this->faker->url(),
                'pages' => $this->faker->numberBetween(1, 20),
                'downloadable' => true,
            ],
            'mime_type' => 'application/pdf',
            'duration' => $this->faker->numberBetween(120, 900), // 2-15 minutos
        ]);
    }

    /**
     * Indicate that the content is audio type.
     */
    public function audio(): static
    {
        return $this->state(fn (array $attributes) => [
            'content_type' => 'audio',
            'content_data' => [
                'audio_url' => $this->faker->url(),
                'transcript' => $this->faker->paragraphs(2, true),
                'chapters' => [
                    ['time' => 0, 'title' => 'Introdução'],
                    ['time' => 120, 'title' => 'Desenvolvimento'],
                    ['time' => 300, 'title' => 'Conclusão'],
                ]
            ],
            'mime_type' => 'audio/mpeg',
            'duration' => $this->faker->numberBetween(180, 900), // 3-15 minutos
        ]);
    }

    /**
     * Indicate that the content has transcript.
     */
    public function withTranscript(): static
    {
        return $this->state(fn (array $attributes) => [
            'transcript' => [
                ['start' => 0, 'end' => 30, 'text' => 'Bem-vindo ao módulo de introdução.'],
                ['start' => 30, 'end' => 60, 'text' => 'Hoje vamos aprender sobre nossa cultura empresarial.'],
                ['start' => 60, 'end' => 90, 'text' => 'A HCP valoriza a inovação e o trabalho em equipe.'],
                ['start' => 90, 'end' => 120, 'text' => 'Vamos explorar nossos valores fundamentais.'],
                ['start' => 120, 'end' => 150, 'text' => 'Obrigado por participar deste módulo.'],
            ],
        ]);
    }

    /**
     * Indicate that the content has interactive markers.
     */
    public function withMarkers(): static
    {
        return $this->state(fn (array $attributes) => [
            'interactive_markers' => [
                ['time' => 30, 'title' => 'Ponto importante', 'content' => 'Informação adicional sobre este tópico'],
                ['time' => 60, 'title' => 'Exemplo prático', 'content' => 'Demonstração do conceito em ação'],
                ['time' => 90, 'title' => 'Resumo', 'content' => 'Principais pontos abordados até agora'],
                ['time' => 120, 'title' => 'Dica', 'content' => 'Dica importante para aplicar o conhecimento'],
            ],
        ]);
    }

    /**
     * Get content data based on content type.
     */
    private function getContentDataForType(string $contentType): array
    {
        return match($contentType) {
            'video' => [
                'video_url' => $this->faker->url(),
                'transcript' => $this->faker->paragraphs(3, true),
                'markers' => [
                    ['time' => 30, 'title' => 'Introdução'],
                    ['time' => 120, 'title' => 'Conceitos principais'],
                    ['time' => 300, 'title' => 'Conclusão'],
                ]
            ],
            'text' => [
                'html_content' => $this->faker->paragraphs(5, true),
                'sections' => [
                    ['title' => 'Introdução', 'content' => $this->faker->paragraph()],
                    ['title' => 'Desenvolvimento', 'content' => $this->faker->paragraphs(2, true)],
                    ['title' => 'Conclusão', 'content' => $this->faker->paragraph()],
                ]
            ],
            'interactive' => [
                'scenarios' => [
                    ['id' => 1, 'title' => 'Cenário 1', 'description' => 'Descrição do cenário'],
                    ['id' => 2, 'title' => 'Cenário 2', 'description' => 'Descrição do cenário'],
                ],
                'interactions' => [
                    ['type' => 'quiz', 'question' => 'Pergunta interativa'],
                    ['type' => 'choice', 'options' => ['Opção A', 'Opção B', 'Opção C']],
                ]
            ],
            'document' => [
                'document_url' => $this->faker->url(),
                'pages' => $this->faker->numberBetween(1, 20),
                'downloadable' => true,
            ],
            'audio' => [
                'audio_url' => $this->faker->url(),
                'transcript' => $this->faker->paragraphs(2, true),
                'chapters' => [
                    ['time' => 0, 'title' => 'Introdução'],
                    ['time' => 120, 'title' => 'Desenvolvimento'],
                    ['time' => 300, 'title' => 'Conclusão'],
                ]
            ],
            default => []
        };
    }

    /**
     * Get MIME type based on content type.
     */
    private function getMimeTypeForContentType(string $contentType): string
    {
        return match($contentType) {
            'video' => 'video/mp4',
            'text' => 'text/html',
            'interactive' => 'application/json',
            'document' => 'application/pdf',
            'audio' => 'audio/mpeg',
            default => 'application/octet-stream'
        };
    }
} 