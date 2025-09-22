<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staf', function (Blueprint $table) {
            $table->bigIncrements('staf_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('cabang_id');
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();

            $table->foreign('user_id')->references('user_id')->on('user')->nullOnDelete();
            $table->foreign('cabang_id')->references('cabang_id')->on('cabang')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staf');
    }
};


