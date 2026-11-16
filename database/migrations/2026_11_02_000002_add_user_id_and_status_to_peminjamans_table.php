<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('peminjamans')) {
            return;
        }

        if (! Schema::hasColumn('peminjamans', 'user_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('peminjamans', 'status')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->string('status', 30)->default('menunggu');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('peminjamans')) {
            return;
        }

        if (Schema::hasColumn('peminjamans', 'status')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('peminjamans', 'user_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
