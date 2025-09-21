<?php
// File: database/migrations/xxxx_xx_xx_add_phone_and_payment_method_to_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('phone_number', 20)->after('guest_name');
            $table->enum('payment_method', ['cash', 'transfer'])->after('total_price');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'payment_method']);
        });
    }
};
