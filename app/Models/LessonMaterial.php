<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\Orderable;

/**
 * Model para materiais complementares de uma aula
 * 
 * @property int $id
 * @property int $lesson_id
 * @property string $title
 * @property string|null $description
 * @property string $type (pdf|doc|slide|link|image)
 * @property string|null $file_path
 * @property string|null $external_url
 * @property int|null $file_size
 * @property string|null $mime_type
 * @property int $order_index
 * @property bool $is_downloadable
 * @property array|null $metadata
 */
class LessonMaterial extends Model
{
    use HasFactory, Orderable;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'type',
        'file_path',
        'external_url',
        'file_size',
        'mime_type',
        'order_index',
        'is_downloadable',
        'metadata',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_downloadable' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento com aula
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Obter URL do material
     */
    public function getUrl(): string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if ($this->file_path) {
            return $this->getSecureFileUrl();
        }

        return '';
    }

    /**
     * Obter URL segura do arquivo
     */
    public function getSecureFileUrl(): string
    {
        if (!$this->file_path) {
            return '';
        }

        // Para materiais downloadable, usar URL temporária
        if ($this->is_downloadable) {
            return Storage::temporaryUrl(
                'materials/' . $this->file_path,
                now()->addHours(2)
            );
        }

        return Storage::url('materials/' . $this->file_path);
    }

    /**
     * Obter URL de download
     */
    public function getDownloadUrl(): ?string
    {
        if (!$this->is_downloadable || !$this->file_path) {
            return null;
        }

        return route('lessons.materials.download', [
            'lesson' => $this->lesson_id,
            'material' => $this->id,
        ]);
    }

    /**
     * Verificar se é arquivo PDF
     */
    public function isPdf(): bool
    {
        return $this->type === 'pdf' || $this->mime_type === 'application/pdf';
    }

    /**
     * Verificar se é documento
     */
    public function isDocument(): bool
    {
        return in_array($this->type, ['doc', 'docx']) || 
               str_contains($this->mime_type ?? '', 'document');
    }

    /**
     * Verificar se é apresentação
     */
    public function isSlide(): bool
    {
        return $this->type === 'slide' || 
               str_contains($this->mime_type ?? '', 'presentation');
    }

    /**
     * Verificar se é link externo
     */
    public function isExternalLink(): bool
    {
        return $this->type === 'link' && !empty($this->external_url);
    }

    /**
     * Verificar se é imagem
     */
    public function isImage(): bool
    {
        return $this->type === 'image' || 
               str_contains($this->mime_type ?? '', 'image');
    }

    /**
     * Obter ícone do tipo de material
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-text',
            'slide' => 'presentation',
            'link' => 'external-link',
            'image' => 'image',
            'video' => 'play-circle',
            'audio' => 'volume-2',
            default => 'file',
        };
    }

    /**
     * Obter cor do tipo de material
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'pdf' => 'red',
            'doc', 'docx' => 'blue',
            'slide' => 'orange',
            'link' => 'green',
            'image' => 'purple',
            'video' => 'indigo',
            'audio' => 'pink',
            default => 'gray',
        };
    }

    /**
     * Obter nome formatado do tipo
     */
    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'pdf' => 'PDF',
            'doc' => 'Documento Word',
            'docx' => 'Documento Word',
            'slide' => 'Apresentação',
            'link' => 'Link Externo',
            'image' => 'Imagem',
            'video' => 'Vídeo',
            'audio' => 'Áudio',
            default => 'Arquivo',
        };
    }

    /**
     * Obter tamanho formatado
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
     * Verificar se o arquivo existe
     */
    public function fileExists(): bool
    {
        if (!$this->file_path) {
            return false;
        }

        return Storage::exists('materials/' . $this->file_path);
    }

    /**
     * Obter caminho completo do arquivo
     */
    public function getFullFilePath(): string
    {
        if (!$this->file_path) {
            return '';
        }

        return Storage::path('materials/' . $this->file_path);
    }

    /**
     * Gerar thumbnail para o material (se aplicável)
     */
    public function getThumbnailUrl(): string
    {
        if ($this->isImage() && $this->file_path) {
            return Storage::url('materials/thumbnails/' . pathinfo($this->file_path, PATHINFO_FILENAME) . '_thumb.jpg');
        }

        if ($this->isPdf()) {
            $thumbnailPath = 'materials/thumbnails/' . pathinfo($this->file_path, PATHINFO_FILENAME) . '_thumb.jpg';
            if (Storage::exists($thumbnailPath)) {
                return Storage::url($thumbnailPath);
            }
        }

        return match($this->type) {
            'pdf' => asset('images/pdf-icon.png'),
            'doc', 'docx' => asset('images/doc-icon.png'),
            'slide' => asset('images/slide-icon.png'),
            'link' => asset('images/link-icon.png'),
            default => asset('images/file-icon.png'),
        };
    }

    /**
     * Registrar download
     */
    public function recordDownload(User $user): void
    {
        // Registrar estatística de download
        $downloads = $this->metadata['downloads'] ?? [];
        $downloads[] = [
            'user_id' => $user->id,
            'downloaded_at' => now()->toISOString(),
            'ip_address' => request()->ip(),
        ];

        $this->update([
            'metadata' => array_merge($this->metadata ?? [], [
                'downloads' => $downloads,
                'download_count' => count($downloads),
            ])
        ]);
    }

    /**
     * Obter estatísticas de download
     */
    public function getDownloadStats(): array
    {
        $downloads = $this->metadata['downloads'] ?? [];
        $uniqueUsers = collect($downloads)->pluck('user_id')->unique()->count();

        return [
            'total_downloads' => count($downloads),
            'unique_users' => $uniqueUsers,
            'last_download' => collect($downloads)->last()['downloaded_at'] ?? null,
        ];
    }

    /**
     * Verificar se pode ser visualizado inline
     */
    public function canViewInline(): bool
    {
        return in_array($this->type, ['pdf', 'image']) || 
               $this->isExternalLink();
    }

    /**
     * Obter configurações de visualização
     */
    public function getViewerConfig(): array
    {
        return [
            'type' => $this->type,
            'url' => $this->getUrl(),
            'downloadable' => $this->is_downloadable,
            'download_url' => $this->getDownloadUrl(),
            'thumbnail' => $this->getThumbnailUrl(),
            'inline_viewable' => $this->canViewInline(),
            'file_size' => $this->formatted_file_size,
            'mime_type' => $this->mime_type,
        ];
    }
}