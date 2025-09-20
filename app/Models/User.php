<?php
// File: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'username_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function cleaningAssignments()
    {
        return $this->hasMany(CleaningNotification::class, 'assigned_to');
    }

    // Helper methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    public function isCleaning()
    {
        return $this->role === 'cleaning';
    }

    public function canManageRooms()
    {
        return in_array($this->role, ['super_admin', 'kasir']);
    }

    public function canViewCleaning()
    {
        return in_array($this->role, ['super_admin', 'cleaning']);
    }
}
