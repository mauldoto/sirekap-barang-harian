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
        Schema::create('akomodasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_referensi')->unique();
            $table->date('tanggal_terbit');
            $table->double('nominal_pengajuan', 8, 2);
            $table->double('nominal_realisasi', 8, 2);
            $table->enum('status', ['created', 'canceled', 'closed'])->default('created');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('id_pemohon')->nullable();
            $table->unsignedBigInteger('input_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akomodasi');
    }
};
