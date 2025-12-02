<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->char('id_payment_history', 8)->primary();
            $table->char('id_users', 8); // Foreign Key ke users.id_users
            $table->decimal('amount', 10, 2); // Jumlah pembayaran
            $table->enum('type', ['withdrawal', 'manual_bonus']); // Asal pembayaran
            $table->char('reference_id', 8); // ID referensi (withdrawal atau bonus_calculation)
            $table->string('payment_method', 50); // Metode pembayaran
            $table->timestamp('payment_date')->useCurrent(); // Tanggal pembayaran
            $table->timestamps(); // created_at, updated_at

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            // Foreign key untuk reference_id akan dibuat secara dinamis atau di kontroler jika bisa mengacu ke beberapa tabel
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_history');
    }
};
