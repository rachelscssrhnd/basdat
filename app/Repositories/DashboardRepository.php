<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function bookingPerBulan()
    {
        return DB::connection('warehouse')->select("
            SELECT 
                d.tahun,
                d.bulan,
                COUNT(f.sk_booking) AS total_booking
            FROM fact_booking f
            JOIN dim_waktu d ON f.sk_waktu = d.sk_waktu
            GROUP BY d.tahun, d.bulan
            ORDER BY d.tahun, d.bulan
        ");
    }

    public function bookingPerCabang()
    {
        return DB::connection('warehouse')->select("
            SELECT 
                c.nama_cabang,
                COUNT(f.sk_booking) AS total_booking
            FROM fact_booking f
            JOIN dim_cabang c ON f.sk_cabang = c.sk_cabang
            GROUP BY c.nama_cabang
        ");
    }

    public function revenuePerKuartal()
    {
        return DB::connection('warehouse')->select("
            SELECT 
                d.tahun,
                d.kuartal,
                SUM(f.jumlah) AS total_pemasukan
            FROM fact_pembayaran f
            JOIN dim_waktu d ON f.sk_waktu = d.sk_waktu
            GROUP BY d.tahun, d.kuartal
            ORDER BY d.tahun, d.kuartal
        ");
    }

    public function revenuePerCabang()
    {
        return DB::connection('warehouse')->select("
            SELECT 
                c.nama_cabang,
                SUM(f.jumlah) AS total_pemasukan
            FROM fact_pembayaran f
            JOIN dim_cabang c ON f.sk_cabang = c.sk_cabang
            GROUP BY c.nama_cabang
        ");
    }

    public function distribusiTes()
    {
        return DB::connection('warehouse')->select("
            SELECT 
                l.nama_tes,
                COUNT(f.sk_hasil) AS jumlah_tes
            FROM fact_hasil_tes f
            JOIN dim_layanan l ON f.sk_layanan = l.sk_layanan
            GROUP BY l.nama_tes
        ");
    }

    public function trenTesPerBulan()
    {
        return DB::connection('warehouse')->select("
            SELECT 
                d.tahun,
                d.bulan,
                COUNT(f.sk_hasil) AS jumlah_tes
            FROM fact_hasil_tes f
            JOIN dim_waktu d ON f.sk_waktu = d.sk_waktu
            GROUP BY d.tahun, d.bulan
            ORDER BY d.tahun, d.bulan
        ");
    }
}
