<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_tes', function (Blueprint $table) {
            $table->bigIncrements('tes_id');
            $table->string('nama_tes');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->text('persiapan_khusus')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_tes');
    }
};


