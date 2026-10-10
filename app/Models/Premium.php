<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    use HasFactory;
    protected $table = 'premiums';

    protected $fillable = [
        'title',
        'pic',
        'audio_file',
        'category',
        'premium'
    ];
    
    protected $casts = [
        'journal_prompts' => 'array', // ensures JSON is converted to array
    ];
}
