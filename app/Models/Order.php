<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_option',
        'delivery_address',
        'delivery_cost',
        'total_amount',
        'payment_intent_id',
        'payment_status',
        'order_status',
        'customer_email',
        'phone_number',
        'first_name',
        'last_name',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
