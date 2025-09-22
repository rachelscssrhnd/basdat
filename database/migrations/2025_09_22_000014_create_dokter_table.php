<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->bigIncrements('dokter_id');
            $table->unsignedBigInteger('cabang_id');
            $table->string('nama');
            $table->string('spesialisasi')->nullable();

            $table->foreign('cabang_id')->references('cabang_id')->on('cabang')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};


