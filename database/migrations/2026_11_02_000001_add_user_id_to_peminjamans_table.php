<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migration ini mengasumsikan tabel peminjaman dari modul sebelumnya sudah ada.
        if (Schema::hasTable('peminjamans') && ! Schema::hasColumn('peminjamans', 'user_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('peminjamans') && Schema::hasColumn('peminjamans', 'user_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
