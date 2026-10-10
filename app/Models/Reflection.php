<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reflection extends Model
{
    protected $fillable = [
        'title',
        'subheadings',
        'pic',
        'audio_file',
        'journal_prompts',
        'premium',
    ];

    protected $casts = [
        'subheadings' => 'array',
        'journal_prompts' => 'array',
        'premium' => 'boolean',
    ];
}
