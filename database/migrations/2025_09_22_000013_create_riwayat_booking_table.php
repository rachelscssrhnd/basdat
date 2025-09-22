<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_booking', function (Blueprint $table) {
            $table->bigIncrements('history_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->dateTime('changed_at')->nullable();

            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnDelete();
            $table->foreign('changed_by')->references('user_id')->on('user')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_booking');
    }
};


