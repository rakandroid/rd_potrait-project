<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudienceTestimonial extends Model
{
    protected $fillable = [
        'name',
        'rating',
        'message',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_visible' => 'boolean',
        ];
    }
}
