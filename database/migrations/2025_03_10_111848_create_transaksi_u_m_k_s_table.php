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
        Schema::create('transaksiumk', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengajuan');
            $table->string('akun_bpr');
            $table->text('nama_akun');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->string('qty');
            $table->text('satuan');
            $table->string('nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_u_m_k_s');
    }
};
