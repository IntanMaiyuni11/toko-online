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
    Schema::table('users', function (Blueprint $table) {
        // Tambahkan kolom baru
        $table->boolean('is_jt')->default(false)->after('roles');
        $table->boolean('is_sicepat')->default(false)->after('is_jt');
        $table->boolean('is_pos')->default(false)->after('is_sicepat');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['is_jt', 'is_sicepat', 'is_pos']);
    });
}
};
