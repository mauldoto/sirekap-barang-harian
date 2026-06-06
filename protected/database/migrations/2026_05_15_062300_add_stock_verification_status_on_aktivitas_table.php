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
        DB::statement("ALTER TABLE aktivitas MODIFY COLUMN status ENUM('waiting', 'progress', 'done', 'cancel', 'stock_out_verification', 'stock_out_verified') DEFAULT 'done'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'verifikator', 'finance', 'jpn') DEFAULT 'jpn'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE aktivitas MODIFY COLUMN status ENUM('waiting', 'progress', 'done', 'cancel') DEFAULT 'done'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'finance', 'jpn') DEFAULT 'jpn'");
    }
};
