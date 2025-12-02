<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pins', function (Blueprint $table) {
            $table->char('id_pins', 8)->primary();
            $table->char('id_users', 8); // Foreign Key ke users.id_users
            $table->integer('balance')->default(0); // Jumlah PIN Normal
            $table->timestamps();

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pins');
    }
};
