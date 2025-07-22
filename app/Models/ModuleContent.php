<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasActiveStatus;
use App\Models\Traits\Orderable;
use App\Models\Traits\FormattedTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User; // Added this import for the new method

/**
 * @property int $id
 * @property int $module_id
 * @property string $title
 * @property string $content_type
 * @property array $content_data
 * @property int $order_index
 * @property bool $is_active
 * @property int $duration
 * @property string $file_path
 * @property int $file_size
 * @property string $mime_type
 * @property array $transcript
 * @property array $interactive_markers
 * @property bool $notes_enabled
 * @property bool $bookmarks_enabled
 */
class ModuleContent extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'title',
        'content_type',
        'content_data',
        'order_index',
        'is_active',
        'duration',
        'file_path',
        'file_size',
        'mime_type',
        'transcript',
        'interactive_markers',
        'notes_enabled',
        'bookmarks_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content_data' => 'array',
        'is_active' => 'boolean',
        'duration' => 'integer',
        'file_size' => 'integer',
        'transcript' => 'array',
        'interactive_markers' => 'array',
        'notes_enabled' => 'boolean',
        'bookmarks_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento: módulo ao qual pertence o conteúdo.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Métodos de negócio
     */

    /**
     * Obter URL do arquivo de conteúdo
     */
    public function getContentUrl(): string
    {
        if (!$this->file_path) {
            return '';
        }

        return match($this->content_type) {
            'video' => asset('storage/modules/videos/' . $this->file_path),
            'audio' => asset('storage/modules/audios/' . $this->file_path),
            'pdf' => asset('storage/modules/pdfs/' . $this->file_path),
            'image' => asset('storage/modules/images/' . $this->file_path),
            default => asset('storage/modules/files/' . $this->file_path)
        };
    }

    /**
     * Obter URL da thumbnail do vídeo
     */
    public function getThumbnailUrl(): string
    {
        if ($this->content_type === 'video' && $this->file_path) {
            $thumbnailPath = str_replace('.mp4', '_thumb.jpg', $this->file_path);
            return asset('storage/modules/thumbnails/' . $thumbnailPath);
        }
        
        return asset('images/content-default.png');
    }

    /**
     * Verificar se é conteúdo de vídeo
     */
    public function isVideo(): bool
    {
        return $this->content_type === 'video';
    }

    /**
     * Verificar se é conteúdo de áudio
     */
    public function isAudio(): bool
    {
        return $this->content_type === 'audio';
    }

    /**
     * Verificar se é PDF
     */
    public function isPdf(): bool
    {
        return $this->content_type === 'pdf';
    }

    /**
     * Verificar se é texto/HTML
     */
    public function isText(): bool
    {
        return in_array($this->content_type, ['text', 'html']);
    }

    /**
     * Obter marcadores interativos ordenados
     */
    public function getInteractiveMarkers(): array
    {
        if (!$this->interactive_markers) {
            return [];
        }

        // Ordenar por tempo
        $markers = $this->interactive_markers;
        usort($markers, fn($a, $b) => ($a['time'] ?? 0) <=> ($b['time'] ?? 0));
        
        return $markers;
    }

    /**
     * Adicionar marcador interativo
     */
    public function addInteractiveMarker(int $timeInSeconds, string $title, string $content, string $type = 'info'): void
    {
        $markers = $this->interactive_markers ?? [];
        $markers[] = [
            'id' => uniqid(),
            'time' => $timeInSeconds,
            'title' => $title,
            'content' => $content,
            'type' => $type, // info, quiz, note, bookmark
            'created_at' => now()->toISOString(),
        ];
        
        $this->update(['interactive_markers' => $markers]);
    }

    /**
     * Obter transcrição por tempo
     */
    public function getTranscriptAtTime(int $timeInSeconds): ?array
    {
        if (!$this->transcript) {
            return null;
        }

        foreach ($this->transcript as $segment) {
            $start = $segment['start'] ?? 0;
            $end = $segment['end'] ?? 0;
            
            if ($timeInSeconds >= $start && $timeInSeconds <= $end) {
                return $segment;
            }
        }
        
        return null;
    }

    /**
     * Buscar na transcrição
     */
    public function searchTranscript(string $query): array
    {
        if (!$this->transcript) {
            return [];
        }

        $results = [];
        $query = strtolower($query);
        
        foreach ($this->transcript as $segment) {
            $text = strtolower($segment['text'] ?? '');
            
            if (str_contains($text, $query)) {
                $results[] = [
                    'time' => $segment['start'] ?? 0,
                    'text' => $segment['text'] ?? '',
                    'highlight' => $this->highlightText($segment['text'] ?? '', $query),
                ];
            }
        }
        
        return $results;
    }

    /**
     * Destacar texto na busca
     */
    private function highlightText(string $text, string $query): string
    {
        return str_ireplace($query, "<mark>{$query}</mark>", $text);
    }

    /**
     * Scopes
     */

    /**
     * Scope para conteúdos ativos
     */
    // Scope Active disponível via trait


    /**
     * Scope ordenado por índice
     */
    // Scope Ordered disponível via trait


    /**
     * Scope por tipo de conteúdo
     */
    // Scope ByType disponível via trait HasCommonScopes


    /**
     * Accessors
     */

    /**
     * Obter duração formatada
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return 'N/A';
        }

        if ($this->duration < 60) {
            return $this->duration . 's';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        if ($seconds === 0) {
            return $minutes . 'min';
        }

        return $minutes . 'min ' . $seconds . 's';
    }

    /**
     * Obter tamanho do arquivo formatado
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Obter tipo de conteúdo formatado
     */
    public function getFormattedContentTypeAttribute(): string
    {
        return match($this->content_type) {
            'video' => 'Vídeo',
            'audio' => 'Áudio/Podcast',
            'pdf' => 'Documento PDF',
            'text' => 'Texto',
            'html' => 'Conteúdo HTML',
            'image' => 'Imagem',
            'infographic' => 'Infográfico',
            default => 'Arquivo'
        };
    }

    /**
     * Obter ícone do tipo de conteúdo
     */
    public function getContentTypeIconAttribute(): string
    {
        return match($this->content_type) {
            'video' => 'play-circle',
            'audio' => 'volume-2',
            'pdf' => 'file-text',
            'text' => 'type',
            'html' => 'code',
            'image' => 'image',
            'infographic' => 'bar-chart',
            default => 'file'
        };
    }

    /**
     * Verifica se o conteúdo está disponível para o usuário.
     */
    public function isAvailableFor(User $user): bool
    {
        // Exemplo: pode ser expandido para lógica de pré-requisito, etc.
        return $this->is_active;
    }
}