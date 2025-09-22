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

        // Jenis Tes
        $tesHematologi = DB::table('jenis_tes')->insertGetId([
            'nama_tes' => 'Hematologi Lengkap',
            'deskripsi' => 'Pemeriksaan darah lengkap',
            'harga' => 150000,
        ]);

        // Parameter untuk tes di atas
        $paramHb = DB::table('parameter_tes')->insertGetId([
            'tes_id' => $tesHematologi,
            'nama_parameter' => 'Hemoglobin',
            'satuan' => 'g/dL',
        ]);

        // Booking
        $bookingId = DB::table('booking')->insertGetId([
            'pasien_id' => $pasienId,
            'cabang_id' => $cabangId,
            'tanggal_booking' => date('Y-m-d'),
            'status_pembayaran' => 'lunas',
            'status_tes' => 'selesai',
        ]);

        // Detail Booking
        DB::table('detail_booking')->insert([
            'booking_id' => $bookingId,
            'tes_id' => $tesHematologi,
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
