<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'youtube_url',
        'is_youtube',
        'file_type',
        'mime_type',
        'file_size',
        'is_public',
        'uploaded_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_youtube' => 'boolean',
        'file_size' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileTypeIconAttribute(): string
    {
        if ($this->is_youtube) {
            return 'fa-brands fa-youtube';
        }

        return match ($this->file_type) {
            'video' => 'fa-play-circle',
            'pdf' => 'fa-file-pdf',
            'word' => 'fa-file-word',
            'excel' => 'fa-file-excel',
            'powerpoint' => 'fa-file-powerpoint',
            default => 'fa-file',
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        } else {
            return $bytes.' bytes';
        }
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (! $this->is_youtube || ! $this->youtube_url) {
            return null;
        }

        // Extract YouTube video ID from various URL formats
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);

        return $matches[1] ?? null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $youtubeId = $this->youtube_id;

        return $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;
    }

    public function views()
    {
        return $this->hasMany(ResourceView::class);
    }

    public function getTotalViewsAttribute(): int
    {
        return $this->views()->count();
    }

    public function getUniqueViewsAttribute(): int
    {
        return $this->views()->distinct('ip_address')->count('ip_address');
    }
}
