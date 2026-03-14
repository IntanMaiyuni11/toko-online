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
    Schema::table('rewards', function (Blueprint $table) {
        // Menambahkan kolom users_id setelah kolom id
        $table->foreignId('users_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('rewards', function (Blueprint $table) {
        $table->dropForeign(['users_id']);
        $table->dropColumn('users_id');
    });
}
};
