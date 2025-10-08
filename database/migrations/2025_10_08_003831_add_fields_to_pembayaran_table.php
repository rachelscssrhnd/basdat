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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('status')->comment('Payment proof file path');
            $table->timestamp('tanggal_upload')->nullable()->after('bukti_pembayaran')->comment('Upload date');
            $table->timestamp('tanggal_konfirmasi')->nullable()->after('tanggal_upload')->comment('Confirmation date');
            $table->text('alasan_reject')->nullable()->after('tanggal_konfirmasi')->comment('Rejection reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['bukti_pembayaran', 'tanggal_upload', 'tanggal_konfirmasi', 'alasan_reject']);
        });
    }
};
