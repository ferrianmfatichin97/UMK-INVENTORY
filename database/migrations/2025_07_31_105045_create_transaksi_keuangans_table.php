<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void {
        Schema::create('transaksi_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id')->unique();       
            $table->string('kode_akun');              
            $table->string('nama_akun');               
            $table->decimal('amount', 18, 2);           
            $table->date('trans_date');                 
            $table->string('description')->nullable();  
            $table->string('source');                   
            $table->string('status')->default('posting'); // posting, delete
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
