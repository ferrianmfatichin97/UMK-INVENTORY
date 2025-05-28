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
         Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('divisi'); 
            $table->string('nama_barang');
            $table->string('nota_dinas'); 
            $table->integer('jumlah');
            $table->string('spesifikasi')->nullable();
            $table->text('alasan');
            $table->enum('urgensi', ['tinggi', 'sedang', 'rendah']);
            $table->date('tanggal_dibutuhkan');
            $table->string('lampiran_file')->nullable(); 
            $table->text('link_toko_online')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'dibeli'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
};
