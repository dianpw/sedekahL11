<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->char('id_logs', 8)->primary();
            $table->char('id_users', 8)->nullable(); // Foreign Key ke users.id_users (bisa null)
            $table->string('action', 100); // Jenis tindakan
            $table->text('description')->nullable(); // Detail tindakan
            $table->string('ip_address', 45)->nullable(); // Alamat IP
            $table->text('user_agent')->nullable(); // User Agent
            $table->timestamp('timestamp')->useCurrent(); // Waktu kejadian

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
