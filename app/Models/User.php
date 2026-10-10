<?php

namespace App\Models;

use App\Helpers\PremiumAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'device_type',
        'admin',
        'blocked',
        'last_login',
        'subscription',
        'therapist',
        'premium_access',
        'profile_image',
        'notification_time',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'admin' => 'boolean',
        'blocked' => 'boolean',
        'therapist' => 'boolean',
    ];

    protected $appends = ['profile_image_url', 'premium_access'];

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return url('storage/app/public/profile_images/'.$this->profile_image);
        }

        return null;
    }

    public function getPremiumAccessAttribute()
    {
        return PremiumAccess::userHasPremium($this);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function plans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
