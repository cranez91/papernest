<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'business_hours' => 'array',
    ];

    public static function getSettings(): self
    {
        return static::first() ?? static::create();
    }
}
