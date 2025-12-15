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
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);
        $userRoleId = DB::table('role')->insertGetId([
            'name' => 'User',
            'slug' => 'user',
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

        // Jenis Tes - replace with provided list
        $providedTests = [
            [100000, 'Tes Rontgen Gigi (Dental I CR)'],
            [150000, 'Tes Rontgen Gigi (Panoramic)'],
            [200000, "Tes Rontgen Gigi (Water's Foto)"],
            [50000, 'Tes Urine'],
            [120000, 'Tes Kehamilan (Anti-Rubella lgG)'],
            [120000, 'Tes Kehamilan (Anti-CMV lgG)'],
            [120000, 'Tes Kehamilan (Anti-HSV1 lgG)'],
            [75000, 'Tes Darah (Hemoglobin)'],
            [90000, 'Tes Darah (Golongan Darah)'],
            [100000, 'Tes Darah (Agregasi Trombosit)'],
        ];
        $tesIds = [];
        foreach ($providedTests as $pt) {
            $tesIds[] = DB::table('jenis_tes')->insertGetId([
                'nama_tes' => $pt[1],
                'deskripsi' => 'Deskripsi ' . $pt[1],
                'harga' => $pt[0],
                'persiapan_khusus' => null,
            ]);
        }
        $tesHematologi = $tesIds[7];
        $tesMetabolic = $tesIds[0];

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
