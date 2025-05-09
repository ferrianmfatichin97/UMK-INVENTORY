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
        Schema::create('pengajuanumk', function (Blueprint $table) {
            $table->id();
            $table->varchar('nomor_pengajuan');
            $table->date('tanggal_pengajuan');
            $table->enum('status', ['acc', 'waiting', 'revisi'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuanUMK');
    }
};
