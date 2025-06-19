<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    protected $fillable = ['admin_id', 'step', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
