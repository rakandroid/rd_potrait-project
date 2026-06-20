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
        'poster_path',
        'sort_order',
        'is_visible',
        'show_in_hero',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'show_in_hero' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->image_path, $this->fallbackMediaUrl());
    }

    public function getPosterUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->poster_path, $this->fallbackPosterUrl());
    }

    private function resolveMediaUrl(?string $mediaPath, string $fallbackUrl): string
    {
        if (! $mediaPath) {
            return $fallbackUrl;
        }

        if (str_starts_with($mediaPath, 'data:')) {
            return $mediaPath;
        }

        if (str_starts_with($mediaPath, 'http')) {
            return $mediaPath;
        }

        $path = ltrim($mediaPath, '/');

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

        if (file_exists(public_path('storage/'.$path)) || file_exists(storage_path('app/public/'.$path))) {
            return asset('storage/'.$path);
        }

        return $fallbackUrl;
    }

    private function fallbackMediaUrl(): string
    {
        if ($this->media_type === 'video') {
            return asset('videos/jzjrPp4LqmWRtjA3wKHe1Fd3Kpc2T12drRK0nmVI.mp4');
        }

        if ($this->placement === 'hero') {
            return asset('images/hero/model-01.jpeg');
        }

        if ($this->placement === 'profile') {
            return file_exists(public_path('images/beni-character.png'))
                ? asset('images/beni-character.png')
                : asset('images/beni-character.jpeg');
        }

        return asset('images/portfolio/beni-profile-camera.jpeg');
    }

    private function fallbackPosterUrl(): string
    {
        return asset('images/portfolio/beni-profile-camera.jpeg');
    }
}
