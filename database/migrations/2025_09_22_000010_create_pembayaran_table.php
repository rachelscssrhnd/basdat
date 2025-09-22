<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('pembayaran_id');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('jumlah', 12, 2)->default(0);
            $table->string('metode_bayar')->nullable();
            $table->string('status')->default('pending');
            $table->date('tanggal_bayar')->nullable();

            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};


