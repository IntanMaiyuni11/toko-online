<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('product_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('products_id')->constrained('products')->onDelete('cascade');
        $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
        $table->text('comment');
        $table->integer('rating')->default(5); // Opsional jika ingin pakai bintang
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
