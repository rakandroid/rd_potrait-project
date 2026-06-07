<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'service',
        'event_date',
        'location',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }
}
