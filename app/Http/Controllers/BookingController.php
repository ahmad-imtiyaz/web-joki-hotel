<?php
// File: app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Room $room)
    {
        // Check if room is available
        if (!$room->isAvailable()) {
            return redirect()->route('dashboard')
                ->with('error', 'Kamar tidak tersedia untuk dipesan!');
        }

        return view('bookings.create', compact('room'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'duration_hours' => 'required|integer|min:1|max:720', // max 30 days
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $room = Room::findOrFail($request->room_id);

        // Check if room is still available
        if (!$room->isAvailable()) {
            return redirect()->route('dashboard')
                ->with('error', 'Kamar sudah tidak tersedia!');
        }

        // Calculate check-in and check-out times
        $checkIn = Carbon::parse($request->booking_date . ' ' . now()->format('H:i:s'));

        // FIX: Convert string to integer explicitly
        $durationHours = (int) $request->duration_hours;
        $checkOut = $checkIn->copy()->addHours($durationHours);

        // Create booking
        $booking = Booking::create([
            'room_id' => $room->id,
            'user_id' => Auth::id(),
            'guest_name' => $request->guest_name,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'duration_hours' => $durationHours, // Use converted integer
            'total_price' => $request->total_price,
            'notes' => $request->notes,
        ]);

        // Update room status
        $room->update(['status' => 'occupied']);

        return redirect()->route('dashboard')
            ->with('success', 'Pemesanan berhasil dibuat untuk kamar ' . $room->room_number . '!');
    }

    public function complete(Booking $booking)
    {
        if (!$booking->isActive()) {
            return redirect()->back()
                ->with('error', 'Pemesanan tidak aktif!');
        }

        // Update booking status
        $booking->update(['status' => 'completed']);

        // Update room status to cleaning
        $booking->room->update(['status' => 'cleaning']);

        // Create cleaning notification
        \App\Models\CleaningNotification::firstOrCreate([
            'room_id' => $booking->room_id,
            'booking_id' => $booking->id,
        ], [
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Pemesanan berhasil diselesaikan! Kamar akan dibersihkan.');
    }
}
