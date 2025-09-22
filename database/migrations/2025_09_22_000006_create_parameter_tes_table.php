<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_tes', function (Blueprint $table) {
            $table->bigIncrements('param_id');
            $table->unsignedBigInteger('tes_id');
            $table->string('nama_parameter');
            $table->string('satuan')->nullable();

            $table->foreign('tes_id')->references('tes_id')->on('jenis_tes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_tes');
    }
};


