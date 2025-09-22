<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('username')->unique();
            $table->string('password_hash');
            $table->unsignedBigInteger('role_id')->nullable();

            $table->foreign('role_id')->references('role_id')->on('role')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};


