<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_tes_header', function (Blueprint $table) {
            $table->bigIncrements('hasil_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->date('tanggal_input')->nullable();

            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnDelete();
            $table->foreign('dibuat_oleh')->references('user_id')->on('user')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_tes_header');
    }
};


