<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bonus_calculations', function (Blueprint $table) {
            $table->char('id_bonus_calculations', 8)->primary();
            $table->char('registration_id', 8); // Foreign Key ke registrations.id_registrations
            $table->string('bonus_member_username', 10); // Username member penerima bonus
            $table->tinyInteger('level'); // 1, 2, 3, 4
            $table->decimal('amount', 10, 2); // Jumlah bonus
            $table->date('calculation_date'); // Tanggal perhitungan
            $table->enum('status', ['calculated', 'paid'])->default('calculated');
            $table->timestamps();

            $table->foreign('registration_id')->references('id_registrations')->on('registrations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bonus_calculations');
    }
};
