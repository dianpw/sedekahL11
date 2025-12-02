<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->char('id_kecamatan', 10)->primary(); // Misal: '3175010' untuk Kec. Pinang
            $table->char('regency_id', 10); // Foreign Key ke regencies.id_kab_kota
            $table->string('name'); // Nama Kecamatan

            $table->foreign('regency_id')->references('id_kab_kota')->on('regencies');
        });
    }

    public function down()
    {
        Schema::dropIfExists('districts');
    }
};
