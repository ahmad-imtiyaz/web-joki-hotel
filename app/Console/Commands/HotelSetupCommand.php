<?php
// File: app/Console/Commands/HotelSetupCommand.php
// Buat dengan: php artisan make:command HotelSetupCommand

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Hash;

class HotelSetupCommand extends Command
{
    protected $signature = 'hotel:setup';
    protected $description = 'Setup initial data for hotel booking system';

    public function handle()
    {
        $this->info('Setting up Hotel Booking System...');

        // Create users if not exist
        $this->info('Creating default users...');

        $users = [
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Kasir Hotel',
                'username' => 'kasir',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ],
            [
                'name' => 'Cleaning Service',
                'username' => 'cleaning',
                'password' => Hash::make('clean123'),
                'role' => 'cleaning',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['username' => $userData['username']], $userData);
            $this->line("✓ User {$userData['username']} created");
        }

        // Create sample rooms if not exist
        $this->info('Creating sample rooms...');

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
        ];

        foreach ($rooms as $roomData) {
            Room::firstOrCreate(['room_number' => $roomData['room_number']], $roomData);
            $this->line("✓ Room {$roomData['room_number']} created");
        }

        $this->info('');
        $this->info('Hotel Booking System setup completed!');
        $this->info('');
        $this->info('Default login credentials:');
        $this->line('Super Admin: superadmin / admin123');
        $this->line('Kasir: kasir / kasir123');
        $this->line('Cleaning: cleaning / clean123');
        $this->info('');
        $this->info('Run: php artisan serve');
        $this->info('Then visit: http://localhost:8000');

        return 0;
    }
}
