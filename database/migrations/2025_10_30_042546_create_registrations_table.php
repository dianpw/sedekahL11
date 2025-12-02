<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->char('id_registrations', 8)->primary();
            $table->char('new_member_id', 8); // Foreign Key ke users.id_users
            $table->string('sponsor_username', 10)->nullable(); // Username sponsor (jika daftar normal)
            $table->string('upline_username', 10)->nullable(); // Username upline (jika daftar normal)
            $table->enum('registration_type', ['normal', 'marketing']); // Jenis PIN
            $table->char('marketing_pin_id', 8)->nullable(); // Foreign Key ke marketing_pins.id_marketing_pins (jika daftar marketing)
            $table->decimal('registration_fee', 10, 2)->nullable(); // Biaya (jika normal)
            $table->decimal('admin_fee', 10, 2)->nullable(); // Bagian admin (jika normal)
            $table->timestamps();

            $table->foreign('new_member_id')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('marketing_pin_id')->references('id_marketing_pins')->on('marketing_pins')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrations');
    }
};
