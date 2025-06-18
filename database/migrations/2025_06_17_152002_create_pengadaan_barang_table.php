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
        Schema::create('pengadaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('divisi');
            $table->string('nota_dinas');
            $table->text('note')->nullable();
            $table->enum('urgensi', ['tinggi', 'sedang', 'rendah']);
            $table->date('tanggal_dibutuhkan');
            $table->string('lampiran_nodin')->nullable();
            $table->string('lampiran_1')->nullable();
            $table->string('lampiran_2')->nullable();
            $table->enum('status', ['diproses', 'selesai', 'ditolak',])->default('diproses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barangs');
    }
};
