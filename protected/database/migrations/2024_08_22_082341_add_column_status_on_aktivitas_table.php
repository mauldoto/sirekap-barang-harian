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
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->enum('status', ['waiting', 'progress', 'done', 'cancel'])->after('tanggal_pulang')->default('done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
};
