<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_booking', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('tes_id');

            $table->primary(['booking_id', 'tes_id']);
            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnDelete();
            $table->foreign('tes_id')->references('tes_id')->on('jenis_tes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_booking');
    }
};


