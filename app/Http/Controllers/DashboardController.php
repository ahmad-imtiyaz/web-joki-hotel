<?php
// File: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\CleaningNotification;
use Illuminate\Support\Facades\Auth; // TAMBAHAN INI

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // FIXED
        $filter = $request->get('filter', 'all');

        // Get rooms with their active bookings
        $roomsQuery = Room::with(['activeBooking', 'activeBooking.user'])
            ->where('is_active', true);

        // Apply filter
        switch ($filter) {
            case 'available':
                $roomsQuery->where('status', 'available');
                break;
            case 'occupied':
                $roomsQuery->where('status', 'occupied');
                break;
            case 'cleaning':
                $roomsQuery->where('status', 'cleaning');
                break;
        }

        $rooms = $roomsQuery->orderBy('room_number')->get();

        // Check for expired bookings and create cleaning notifications
        $this->checkExpiredBookings();

        // Get statistics
        $stats = [
            'total_rooms' => Room::where('is_active', true)->count(),
            'available_rooms' => Room::where('status', 'available')->where('is_active', true)->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->where('is_active', true)->count(),
            'cleaning_rooms' => Room::where('status', 'cleaning')->where('is_active', true)->count(),
        ];

        // Get pending cleaning notifications count for badge
        $pendingCleaning = CleaningNotification::where('status', 'pending')->count();

        return view('dashboard.index', compact('rooms', 'filter', 'user', 'stats', 'pendingCleaning'));
    }

    private function checkExpiredBookings()
    {
        $expiredBookings = Booking::where('status', 'active')
            ->where('check_out', '<=', now())
            ->get();

        foreach ($expiredBookings as $booking) {
            // Update booking status
            $booking->update(['status' => 'completed']);

            // Update room status
            $booking->room->update(['status' => 'cleaning']);

            // Create cleaning notification if not exists
            CleaningNotification::firstOrCreate([
                'room_id' => $booking->room_id,
                'booking_id' => $booking->id,
            ], [
                'status' => 'pending',
            ]);
        }
    }
}
