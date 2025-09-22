<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_tes_value', function (Blueprint $table) {
            $table->bigIncrements('hasil_value_id');
            $table->unsignedBigInteger('hasil_id');
            $table->unsignedBigInteger('param_id');
            $table->string('nilai_hasil')->nullable();

            $table->foreign('hasil_id')->references('hasil_id')->on('hasil_tes_header')->cascadeOnDelete();
            $table->foreign('param_id')->references('param_id')->on('parameter_tes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_tes_value');
    }
};


