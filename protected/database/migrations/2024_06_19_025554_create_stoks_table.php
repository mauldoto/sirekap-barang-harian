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
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_aktivitas')->nullable();
            $table->string('no_referensi')->unique();
            $table->date('tanggal');
            $table->enum('type', ['masuk', 'keluar']);
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('input_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
