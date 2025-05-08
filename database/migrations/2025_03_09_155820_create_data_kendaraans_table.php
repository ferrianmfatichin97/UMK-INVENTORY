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
        Schema::create('data_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan')->nullable();
            $table->string('merk')->nullable();
            $table->string('type')->nullable();
            $table->string('no_rangka')->nullable();
            $table->string('no_registrasi')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('kantor_cabang')->nullable();
            $table->date('jadwal_pajak')->nullable();
            $table->string('perusahaan_asuransi')->nullable();
            $table->date('asuransi_mulai')->nullable();
            $table->date('asuransi_akhir')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kendaraan');
    }
};
