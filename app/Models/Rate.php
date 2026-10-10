<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'value1',
        'value2',
        'comment',
        'user_id',
    ];

    // Relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
