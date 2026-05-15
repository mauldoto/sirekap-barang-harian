<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_carts', function (Blueprint $table) {
            $table->unsignedBigInteger('id_stok');
            $table->unsignedBigInteger('id_barang');
            $table->boolean('is_new')->default(true);
            $table->double('qty')->default(0);
            $table->double('qty_used')->default(0);
            $table->unsignedBigInteger('id_gudang');
            $table->double('harga', 8, 2)->default(0);
            $table->timestamps();
            $table->index('id_stok');
            $table->index('id_barang');
            $table->index('id_gudang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_carts');
    }
};
