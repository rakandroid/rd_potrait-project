<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioPhoto extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'placement',
        'media_type',
        'image_path',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        $path = ltrim($this->image_path, '/');

        $publicCandidates = [
            $path,
            'images/'.$path,
            'videos/'.$path,
        ];

        if (str_starts_with($path, 'portfolio/')) {
            $publicCandidates[] = 'images/'.$path;
            $publicCandidates[] = 'videos/'.basename($path);
        }

        if (str_starts_with($path, 'hero/')) {
            $publicCandidates[] = 'images/'.$path;
        }

        foreach (array_unique($publicCandidates) as $candidate) {
            if (file_exists(public_path($candidate))) {
                return asset($candidate);
            }
        }

        return '/storage/'.ltrim($this->image_path, '/');
    }
}
