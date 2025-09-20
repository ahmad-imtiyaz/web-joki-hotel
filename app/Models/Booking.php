<?php
// File: app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'guest_name',
        'check_in',
        'check_out',
        'total_price',
        'duration_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cleaningNotification()
    {
        return $this->hasOne(CleaningNotification::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isExpired()
    {
        return now() >= $this->check_out;
    }

    public function getRemainingTime()
    {
        if (!$this->isActive()) {
            return null;
        }

        $now = now();
        $checkOut = $this->check_out;

        if ($now >= $checkOut) {
            return 'Expired';
        }

        $diff = $now->diff($checkOut);
        return $diff->format('%h jam %i menit %s detik');
    }

    public function getDurationInWords()
    {
        if ($this->duration_hours < 24) {
            return $this->duration_hours . ' jam';
        } else {
            $days = floor($this->duration_hours / 24);
            $hours = $this->duration_hours % 24;
            return $days . ' hari' . ($hours > 0 ? ' ' . $hours . ' jam' : '');
        }
    }
}
