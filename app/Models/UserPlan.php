<?php

// app/Models/UserPlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id',
        'plan_type',
        'price',
        'duration_days',
        'status',
        'start_date',
        'end_date',
        'payment_intent_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
