<?php
// File: app/Http/Controllers/CleaningController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CleaningNotification;

class CleaningController extends Controller
{
    public function index()
    {
        $notifications = CleaningNotification::with(['room', 'booking', 'booking.user', 'assignedUser'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('cleaning.index', compact('notifications'));
    }

    public function accept(CleaningNotification $notification)
    {
        if (!$notification->isPending()) {
            return redirect()->back()
                ->with('error', 'Notifikasi sudah diproses!');
        }

        $notification->markAsInProgress();

        return redirect()->back()
            ->with('success', 'Tugas pembersihan diterima untuk kamar ' . $notification->room->room_number . '!');
    }

    public function complete(CleaningNotification $notification)
    {
        if ($notification->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Tugas sudah selesai!');
        }

        $notification->markAsCompleted();

        return redirect()->back()
            ->with('success', 'Pembersihan kamar ' . $notification->room->room_number . ' berhasil diselesaikan!');
    }
}
