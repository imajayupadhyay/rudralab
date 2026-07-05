<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $fillable = [
        'page',
        'section',
        'key',
        'type',
        'value',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
