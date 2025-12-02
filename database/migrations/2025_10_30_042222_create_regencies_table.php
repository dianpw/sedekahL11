<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regencies', function (Blueprint $table) {
            $table->char('id_kab_kota', 10)->primary(); // Misal: '3175' untuk Kota Tangerang
            $table->char('province_id', 10); // Foreign Key ke provinces.id_provinsi
            $table->string('name'); // Nama Kabupaten/Kota

            $table->foreign('province_id')->references('id_provinsi')->on('provinces');
        });
    }

    public function down()
    {
        Schema::dropIfExists('regencies');
    }
};
