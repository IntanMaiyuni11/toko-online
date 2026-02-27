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
    Schema::create('point_histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
        $table->integer('amount'); // Angka positif untuk tambah, negatif untuk kurang
        $table->string('description'); // Contoh: "Poin belanja" atau "Tukar Reward"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
