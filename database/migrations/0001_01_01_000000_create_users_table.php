<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id_users', 8)->primary(); // VARCHAR(8) Primary Key
            $table->string('username', 10)->unique(); // VARCHAR(10) Unique
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'member']); // ENUM
            $table->string('dana_account', 20)->nullable(); // VARCHAR(20) - Nomor HP Dana
            $table->boolean('is_active')->default(true); // BOOLEAN
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
