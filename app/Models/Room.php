<?php
// File: app/Models/Room.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_name',
        'image',
        'price',
        'description',
        'status',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBooking()
    {
        return $this->hasOne(Booking::class)->where('status', 'active');
    }

    public function cleaningNotifications()
    {
        return $this->hasMany(CleaningNotification::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    public function needsCleaning()
    {
        return $this->status === 'cleaning';
    }

    public function getCurrentBooking()
    {
        return $this->activeBooking;
    }

    public function getRemainingTime()
    {
        $booking = $this->getCurrentBooking();
        if (!$booking) {
            return null;
        }

        $now = now();
        $checkOut = $booking->check_out;

        if ($now >= $checkOut) {
            return 'Expired';
        }

        $diff = $now->diff($checkOut);
        return $diff->format('%h:%i:%s');
    }
}
