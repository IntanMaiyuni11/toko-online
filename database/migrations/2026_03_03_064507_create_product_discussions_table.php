<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('product_discussions', function (Blueprint $table) {
        $table->id();
        $table->integer('products_id');
        $table->integer('users_id');
        $table->text('comment');
        $table->integer('parent_id')->nullable(); // Untuk jawaban penjual
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_discussions');
    }
};
