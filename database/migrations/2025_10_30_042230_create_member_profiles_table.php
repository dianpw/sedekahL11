<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->char('id_member_profiles', 8)->primary();
            $table->char('id_users', 8)->unique(); // Foreign Key ke users.id_users
            $table->string('full_name');
            $table->string('phone_number', 20);
            $table->text('address')->nullable();
            $table->string('id_sponsor', 10)->nullable(); // Username sponsor
            $table->string('id_upline', 10)->nullable(); // Username upline
            $table->char('provinsi_id', 10); // Foreign Key ke provinces.id_provinsi
            $table->char('kab_kota_id', 10); // Foreign Key ke regencies.id_kab_kota
            $table->char('kecamatan_id', 10); // Foreign Key ke districts.id_kecamatan
            $table->char('desa_id', 10); // Foreign Key ke villages.id_desa
            $table->timestamps();

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('provinsi_id')->references('id_provinsi')->on('provinces');
            $table->foreign('kab_kota_id')->references('id_kab_kota')->on('regencies');
            $table->foreign('kecamatan_id')->references('id_kecamatan')->on('districts');
            $table->foreign('desa_id')->references('id_desa')->on('villages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_profiles');
    }
};
