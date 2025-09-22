<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_tes', function (Blueprint $table) {
            $table->unsignedBigInteger('tes_id');
            $table->unsignedBigInteger('param_id');

            $table->primary(['tes_id', 'param_id']);
            $table->foreign('tes_id')->references('tes_id')->on('jenis_tes')->cascadeOnDelete();
            $table->foreign('param_id')->references('param_id')->on('parameter_tes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tes');
    }
};


