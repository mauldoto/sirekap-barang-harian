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
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lokasi');
            $table->unsignedBigInteger('id_sub_lokasi');
            $table->string('no_referensi')->unique();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_berangkat');
            $table->date('tanggal_pulang');
            $table->unsignedBigInteger('input_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};
