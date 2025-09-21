<?php
// File: database/seeders/BookingTestSeeder.php
// Jalankan: php artisan db:seed --class=BookingTestSeeder

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class BookingTestSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();
        $users = User::whereIn('role', ['superadmin', 'kasir'])->get();

        if ($rooms->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Pastikan ada data rooms dan users dengan role superadmin/kasir');
            return;
        }

        // Generate booking data untuk testing laporan
        $guestNames = [
            'Ahmad Sutrisno',
            'Siti Nurhaliza',
            'Budi Prasetyo',
            'Rina Sari',
            'Joko Widodo',
            'Maya Sari',
            'Andi Kurniawan',
            'Dewi Permata',
            'Rizki Ramadhan',
            'Fitri Handayani',
            'Doni Setiawan',
            'Lina Marlina'
        ];

        for ($i = 0; $i < 50; $i++) {
            $checkIn = Carbon::now()->subDays(rand(1, 30))->addHours(rand(10, 20));
            $duration = rand(2, 12); // 2-12 jam
            $checkOut = $checkIn->copy()->addHours($duration);
            $room = $rooms->random();
            $status = ['completed', 'completed', 'completed', 'active'][(rand(0, 3))]; // 75% completed

            Booking::create([
                'room_id' => $room->id,
                'user_id' => $users->random()->id,
                'guest_name' => $guestNames[array_rand($guestNames)],
                'guest_phone' => '08' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $room->price * $duration,
                'status' => $status,
                'created_at' => $checkIn->copy()->subMinutes(rand(10, 60)),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('50 booking test records created successfully!');
    }
}
