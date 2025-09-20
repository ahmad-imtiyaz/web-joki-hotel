<?php
// File: app/Models/CleaningNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // TAMBAHAN INI

class CleaningNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'booking_id',
        'status',
        'assigned_to',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function markAsCompleted($userId = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'assigned_to' => $userId ?? Auth::id(), // FIXED
        ]);

        // Update room status to available
        $this->room()->update(['status' => 'available']);
    }

    public function markAsInProgress($userId = null)
    {
        $this->update([
            'status' => 'in_progress',
            'assigned_to' => $userId ?? Auth::id(), // FIXED
        ]);
    }
}
