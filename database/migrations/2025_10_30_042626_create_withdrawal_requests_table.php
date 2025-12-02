<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->char('id_withdrawal_requests', 8)->primary();
            $table->char('id_users', 8); // Foreign Key ke users.id_users
            $table->decimal('amount', 10, 2); // Jumlah penarikan
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->useCurrent(); // Waktu pengajuan
            $table->timestamp('processed_at')->nullable(); // Waktu diproses
            $table->timestamp('paid_at')->nullable(); // Waktu dibayar
            $table->timestamps(); // created_at, updated_at

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
