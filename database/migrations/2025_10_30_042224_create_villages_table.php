<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->char('id_desa', 10)->primary(); // Misal: '3175010001' untuk Kel. Pinang
            $table->char('district_id', 10); // Foreign Key ke districts.id_kecamatan
            $table->string('name'); // Nama Desa/Kelurahan

            $table->foreign('district_id')->references('id_kecamatan')->on('districts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('villages');
    }
};
