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
       Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
        
        // Isi Chat
        $table->text('message')->nullable(); // Untuk teks
        $table->string('image')->nullable();   // Untuk path gambar
        
        // Lampiran (Nullable karena tidak selalu dikirim)
        $table->foreignId('products_id')->nullable()->constrained('products')->onDelete('set null');
        $table->foreignId('transactions_id')->nullable()->constrained('transactions')->onDelete('set null');
        
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
