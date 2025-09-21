<?php
// Buat migration: php artisan make:migration add_indexes_to_bookings_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Index untuk optimasi query laporan
            $table->index(['created_at', 'status'], 'idx_created_status');
            $table->index(['check_in', 'check_out'], 'idx_checkin_checkout');
            $table->index(['status', 'total_price'], 'idx_status_price');
            $table->index('guest_name', 'idx_guest_name');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_created_status');
            $table->dropIndex('idx_checkin_checkout');
            $table->dropIndex('idx_status_price');
            $table->dropIndex('idx_guest_name');
        });
    }
};
