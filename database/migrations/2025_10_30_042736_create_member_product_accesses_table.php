<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_product_access', function (Blueprint $table) {
            $table->char('id_member_product_access', 8)->primary();
            $table->char('id_users', 8); // Foreign Key ke users.id_users
            $table->char('product_id', 8); // Foreign Key ke digital_products.id_digital_products
            $table->timestamp('granted_at')->useCurrent(); // Waktu akses diberikan
            $table->timestamps(); // created_at, updated_at

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id_digital_products')->on('digital_products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_product_access');
    }
};
