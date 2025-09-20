<?php
// File: app/Http/Controllers/RoomController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function manage(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $roomsQuery = Room::with(['activeBooking', 'activeBooking.user'])
            ->where('is_active', true);

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

        $rooms = $roomsQuery->orderBy('room_number')->paginate(10);

        return view('rooms.manage', compact('rooms', 'filter'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number',
            'room_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['room_number', 'room_name', 'price', 'description']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
            $data['image'] = $imagePath;
        }

        Room::create($data);

        return redirect()->route('rooms.manage')
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => ['required', 'string', Rule::unique('rooms')->ignore($room->id)],
            'room_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,occupied,cleaning,maintenance',
        ]);

        $data = $request->only(['room_number', 'room_name', 'price', 'description', 'status']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }

            $imagePath = $request->file('image')->store('rooms', 'public');
            $data['image'] = $imagePath;
        }

        $room->update($data);

        return redirect()->route('rooms.manage')
            ->with('success', 'Kamar berhasil diupdate!');
    }

    public function destroy(Room $room)
    {
        // Check if room has active booking
        if ($room->activeBooking) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kamar yang sedang digunakan!');
        }

        // Delete image
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->update(['is_active' => false]);

        return redirect()->route('rooms.manage')
            ->with('success', 'Kamar berhasil dihapus!');
    }
}
