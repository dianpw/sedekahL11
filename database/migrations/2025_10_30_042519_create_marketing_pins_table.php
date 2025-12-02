<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketing_pins', function (Blueprint $table) {
            $table->char('id_marketing_pins', 8)->primary();
            $table->string('pin_code')->unique(); // Kode unik PIN Marketing
            $table->char('admin_issuer_id', 8); // Foreign Key ke users.id_users (admin)
            $table->char('assigned_id_users', 8)->nullable(); // Foreign Key ke users.id_users (member)
            $table->dateTime('valid_until'); // Tanggal kadaluarsa
            $table->boolean('is_used')->default(false); // Status penggunaan
            $table->timestamps();

            $table->foreign('admin_issuer_id')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('assigned_id_users')->references('id_users')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketing_pins');
    }
};
