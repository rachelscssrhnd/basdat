<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('booking_id');
            $table->unsignedBigInteger('pasien_id');
            $table->unsignedBigInteger('cabang_id');
            $table->date('tanggal_booking')->nullable();
            $table->string('status_pembayaran')->default('belum_bayar');
            $table->string('status_tes')->default('menunggu');

            $table->foreign('pasien_id')->references('pasien_id')->on('pasien')->cascadeOnDelete();
            $table->foreign('cabang_id')->references('cabang_id')->on('cabang')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};


