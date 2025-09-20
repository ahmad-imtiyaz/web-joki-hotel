<?php
// File: database/migrations/2014_10_12_000000_create_users_table.php
// Ganti content file ini dengan yang baru

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            // $table->string('email')->unique(); // HAPUS BARIS INI
            // $table->timestamp('email_verified_at')->nullable(); // HAPUS BARIS INI
            $table->string('password');
            $table->enum('role', ['super_admin', 'kasir', 'cleaning'])->default('kasir');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
