<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom.
     */
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            // Menambahkan kolom seller_reply setelah kolom comment
            $table->text('seller_reply')->nullable()->after('comment');
        });
    }

    /**
     * Batalkan migrasi (rollback) untuk menghapus kolom.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('seller_reply');
        });
    }
};