<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->integer('sesi')->nullable()->after('tanggal_booking')->comment('Session: 1=08:00-10:00, 2=10:00-12:00, 3=13:00-15:00, 4=15:00-17:00');
            $table->text('alasan_reject')->nullable()->after('status_tes')->comment('Reason for rejection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn(['sesi', 'alasan_reject']);
        });
    }
};
