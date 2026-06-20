<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'backsound_path',
        'instagram_url',
        'tiktok_url',
    ];

    public static function current(): self
    {
        return self::query()->firstOrCreate([], [
            'backsound_path' => 'audio/until-i-found-you-violin-cover.mp3',
            'instagram_url' => 'https://www.instagram.com/rd_potrait?igsh=MXA4NDV0emJjN2Nsag==',
            'tiktok_url' => 'https://www.tiktok.com/@r.benidarmansyah?lang=en',
        ]);
    }

    public function getBacksoundUrlAttribute(): string
    {
        if (! $this->backsound_path) {
            return asset('audio/until-i-found-you-violin-cover.mp3');
        }

        if (str_starts_with($this->backsound_path, 'http')) {
            return $this->backsound_path;
        }

        return asset(ltrim($this->backsound_path, '/'));
    }
}
