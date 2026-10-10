<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class IosSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'product_id',
        'transaction_id',
        'original_transaction_id',
        'environment',
        'expires_at',
        'last_verified_at',
        'raw',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_verified_at' => 'datetime',
        'raw' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActiveForDevice($query, string $deviceId)
    {
        return $query
            ->where('device_id', $deviceId)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', Carbon::now());
    }

    public function scopeActiveForUser($query, int $userId)
    {
        return $query
            ->where('user_id', $userId)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', Carbon::now());
    }
}

