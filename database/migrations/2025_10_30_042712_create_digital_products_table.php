<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('digital_products', function (Blueprint $table) {
            $table->char('id_digital_products', 8)->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path'); // Path file di storage
            $table->string('file_type', 10); // 'video', 'pdf', dll
            $table->enum('access_level', ['all_members', 'specific_member'])->default('all_members');
            $table->char('created_by', 8); // Foreign Key ke users.id_users (admin)
            $table->timestamps();

            $table->foreign('created_by')->references('id_users')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('digital_products');
    }
};
