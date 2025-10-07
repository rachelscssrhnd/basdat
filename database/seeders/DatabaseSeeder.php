<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $adminRoleId = DB::table('role')->insertGetId([
            'nama_role' => 'Administrator',
        ]);
        $userRoleId = DB::table('role')->insertGetId([
            'nama_role' => 'User',
        ]);

        // Users
        $adminUserId = DB::table('user')->insertGetId([
            'username' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'role_id' => $adminRoleId,
        ]);

        $userId = DB::table('user')->insertGetId([
            'username' => 'user',
            'password_hash' => Hash::make('user123'),
            'role_id' => $userRoleId,
        ]);

        // Cabang
        $cabangId = DB::table('cabang')->insertGetId([
            'nama_cabang' => 'Cabang Utama',
            'alamat' => 'Jl. Contoh No.1',
        ]);

        // Pasien
        $pasienId = DB::table('pasien')->insertGetId([
            'nama' => 'Budi',
            'tgl_lahir' => '1990-01-01',
            'email' => 'budi@example.com',
            'no_hp' => '08123456789',
            'user_id' => $userId,
        ]);

        // Jenis Tes
        $tesHematologi = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Basic Health Panel',
            'deskripsi' => '12 tests included - Complete blood count, basic metabolic panel',
            'harga' => 89000,
            'persiapan_khusus' => 'Fasting required for 8-12 hours',
        ]);

        $tesMetabolic = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Complete Metabolic Panel',
            'deskripsi' => '20 tests included - Comprehensive metabolic assessment',
            'harga' => 129000,
            'persiapan_khusus' => 'Fasting required for 8-12 hours',
        ]);

        $tesImmunity = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Immunity Checkup',
            'deskripsi' => '8 tests included - Immune system assessment',
            'harga' => 75000,
            'persiapan_khusus' => 'No special preparation required',
        ]);

        $tesCholesterol = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Cholesterol Panel',
            'deskripsi' => '4 tests included - Complete cholesterol analysis',
            'harga' => 45000,
            'persiapan_khusus' => 'Fasting required for 12 hours',
        ]);

        $tesThyroid = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Thyroid Panel',
            'deskripsi' => '3 tests included - Thyroid function assessment',
            'harga' => 65000,
            'persiapan_khusus' => 'No special preparation required',
        ]);

        $tesEnergy = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Energy & Vitality',
            'deskripsi' => '6 tests included - Energy level assessment',
            'harga' => 95000,
            'persiapan_khusus' => 'Fasting required for 8 hours',
        ]);

        $tesVision = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Vision Health',
            'deskripsi' => '5 tests included - Eye health assessment',
            'harga' => 55000,
            'persiapan_khusus' => 'No special preparation required',
        ]);

        $tesDiabetes = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Diabetes Monitoring',
            'deskripsi' => '4 tests included - Blood sugar monitoring',
            'harga' => 35000,
            'persiapan_khusus' => 'Fasting required for 8 hours',
        ]);

        $tesLiver = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Liver Function Test',
            'deskripsi' => '6 tests included - Liver health assessment',
            'harga' => 85000,
            'persiapan_khusus' => 'Fasting required for 8 hours',
        ]);

        $tesKidney = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Kidney Function Test',
            'deskripsi' => '5 tests included - Kidney health assessment',
            'harga' => 75000,
            'persiapan_khusus' => 'No special preparation required',
        ]);

        // Parameter untuk tes di atas
        $paramHb = DB::table('parameter_tes')->insertGetId([
            'tes_id' => $tesHematologi,
            'nama_parameter' => 'Hemoglobin',
            'satuan' => 'g/dL',
        ]);

        $paramWBC = DB::table('parameter_tes')->insertGetId([
            'tes_id' => $tesHematologi,
            'nama_parameter' => 'WBC Count',
            'satuan' => '×10³/µL',
        ]);

        $paramGlucose = DB::table('parameter_tes')->insertGetId([
            'tes_id' => $tesMetabolic,
            'nama_parameter' => 'Fasting Glucose',
            'satuan' => 'mg/dL',
        ]);

        $paramCholesterol = DB::table('parameter_tes')->insertGetId([
            'tes_id' => $tesMetabolic,
            'nama_parameter' => 'Total Cholesterol',
            'satuan' => 'mg/dL',
        ]);

        // Booking
        $bookingId = DB::table('booking')->insertGetId([
            'pasien_id' => $pasienId,
            'cabang_id' => $cabangId,
            'tanggal_booking' => date('Y-m-d'),
            'status_pembayaran' => 'paid',
            'status_tes' => 'completed',
        ]);

        // Detail Booking
        DB::table('detail_booking')->insert([
            'booking_id' => $bookingId,
            'tes_id' => $tesHematologi,
        ]);

        // Payment
        DB::table('pembayaran')->insert([
            'booking_id' => $bookingId,
            'metode_bayar' => 'bank_transfer',
            'jumlah' => 89000,
            'status' => 'paid',
            'tanggal_bayar' => date('Y-m-d H:i:s'),
        ]);

        // Hasil Tes Header
        $hasilId = DB::table('hasil_tes_header')->insertGetId([
            'booking_id' => $bookingId,
            'dibuat_oleh' => $adminUserId,
            'tanggal_input' => date('Y-m-d'),
        ]);

        // Hasil Tes Value
        DB::table('hasil_tes_value')->insert([
            'hasil_id' => $hasilId,
            'param_id' => $paramHb,
            'nilai_hasil' => '13.5',
        ]);
    }
}
