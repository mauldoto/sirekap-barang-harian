<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE aktivitas MODIFY COLUMN status ENUM('waiting', 'progress', 'done', 'cancel', 'stock_verification', 'stock_verified') DEFAULT 'done'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE aktivitas MODIFY COLUMN status ENUM('waiting', 'progress', 'done', 'cancel') DEFAULT 'done'");
    }
};
