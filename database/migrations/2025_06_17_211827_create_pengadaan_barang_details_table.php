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
        Schema::create('pengadaan_barang_details', function (Blueprint $table) {
            $table->id();
            $table->string('pengadaan_barang_id');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('spesifikasi')->nullable();
            $table->string('link')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('harga', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barang_details');
    }
};
