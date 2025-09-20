<?php
// File: database/seeders/RoomSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            [
                'room_number' => '101',
                'room_name' => 'Deluxe Room 101',
                'price' => 150000,
                'description' => 'Kamar deluxe dengan fasilitas lengkap',
                'status' => 'available',
            ],
            [
                'room_number' => '102',
                'room_name' => 'Standard Room 102',
                'price' => 100000,
                'description' => 'Kamar standard yang nyaman',
                'status' => 'available',
            ],
            [
                'room_number' => '103',
                'room_name' => 'Suite Room 103',
                'price' => 250000,
                'description' => 'Kamar suite mewah dengan balkon',
                'status' => 'available',
            ],
            [
                'room_number' => '201',
                'room_name' => 'Deluxe Room 201',
                'price' => 175000,
                'description' => 'Kamar deluxe lantai 2 dengan pemandangan kota',
                'status' => 'available',
            ],
            [
                'room_number' => '202',
                'room_name' => 'Standard Room 202',
                'price' => 120000,
                'description' => 'Kamar standard lantai 2',
                'status' => 'available',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
