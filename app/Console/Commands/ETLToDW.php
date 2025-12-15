<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ETLToDW extends Command
{
    protected $signature = 'etl:dw';
    protected $description = 'ETL dari DB operasional ke Data Warehouse';

    public function handle()
    {
        $this->info("Starting ETL...");

        $this->loadDimWaktu();
        $this->loadDimBooking();
        $this->loadDimLayanan();
        $this->loadDimCabang();
        $this->loadFactHasilTes();
        $this->loadFactPembayaran();
        $this->loadFactBooking();

        $this->info("ETL selesai!");
    }

    // =========================
    // DIMENSION LOAD FUNCTIONS
    // =========================

    private function loadDimWaktu()
    {
        $bookings = DB::table('booking')->get();

        foreach ($bookings as $b) {
            DB::connection('warehouse')->table('dim_waktu')
                ->updateOrInsert(
                    ['sk_waktu' => $b->booking_id], // sk_waktu bisa auto increment / surrogate key
                    ['tanggal_booking' => $b->tanggal_booking]
                );
        }

        $this->info("dim_waktu loaded");
    }

    private function loadDimBooking()
    {
        $bookings = DB::table('booking')->get();

        foreach ($bookings as $b) {
            DB::connection('warehouse')->table('dim_booking')
                ->updateOrInsert(
                    ['sk_booking' => $b->booking_id],
                    [
                        'booking_id' => $b->booking_id,
                        'pasien_id' => $b->pasien_id,
                        'tanggal_booking' => $b->tanggal_booking
                    ]
                );
        }

        $this->info("dim_booking loaded");
    }

    private function loadDimLayanan()
    {
        $layanan = DB::table('jenis_tes')->get();

        foreach ($layanan as $l) {
            DB::connection('warehouse')->table('dim_layanan')
                ->updateOrInsert(
                    ['sk_layanan' => $l->tes_id],
                    [
                        'tes_id' => $l->tes_id,
                        'nama_tes' => $l->nama_tes,
                        'harga' => $l->harga,
                        'deskripsi' => $l->deskripsi
                    ]
                );
        }

        $this->info("dim_layanan loaded");
    }

    private function loadDimCabang()
    {
        $cabangs = DB::table('cabang')->get();

        foreach ($cabangs as $c) {
            DB::connection('warehouse')->table('dim_cabang')
                ->updateOrInsert(
                    ['sk_cabang' => $c->cabang_id],
                    [
                        'cabang_id' => $c->cabang_id,
                        'nama_cabang' => $c->nama_cabang,
                        'alamat' => $c->alamat
                    ]
                );
        }

        $this->info("dim_cabang loaded");
    }

    // =========================
    // FACT LOAD FUNCTIONS
    // =========================

    private function loadFactHasilTes()
    {
    $headers = DB::table('hasil_tes_header')->get();

    foreach ($headers as $h_header) {
        $values = DB::table('hasil_tes_value')
            ->where('hasil_id', $h_header->hasil_id)
            ->get();

        foreach ($values as $h_value) {

            // Ambil tes_id dari detail_booking
            $tes_id = DB::table('detail_booking')
                ->where('booking_id', $h_header->booking_id)
                ->value('tes_id'); // asumsikan 1 booking 1 tes

            $sk_layanan = DB::table('dim_layanan')
                ->where('tes_id', $tes_id)
                ->value('sk_layanan');

            // Pisahkan nilai_hasil menjadi angka dan satuan
            preg_match('/([\d.]+)\s*(.*)/', $h_value->nilai_hasil, $matches);
            $angka = isset($matches[1]) ? (float)$matches[1] : null;
            $satuan = isset($matches[2]) ? $matches[2] : null;

            DB::connection('warehouse')->table('fact_hasil_tes')
                ->updateOrInsert(
                    ['sk_hasil' => $h_value->hasil_value_id],
                    [
                        'sk_waktu' => $h_header->booking_id, // bisa diganti dengan sk_waktu dari dim_waktu
                        'sk_booking' => $h_header->booking_id,
                        'sk_layanan' => $sk_layanan,
                        'hasil_id' => $h_value->hasil_id,
                        'param_id' => $h_value->param_id,
                        'nilai_hasil' => $angka,  // DECIMAL
                        'satuan_hasil' => $satuan // VARCHAR
                    ]
                );
        }
    }

    $this->info("fact_hasil_tes loaded with nilai and satuan separated");
    }

    private function loadFactPembayaran()
    {
    $payments = DB::table('pembayaran')->get();

    foreach ($payments as $p) {
        // Ambil booking terkait
        $booking = DB::table('booking')
            ->where('booking_id', $p->booking_id)
            ->first();

        if (!$booking) continue; // skip jika booking tidak ditemukan

        // Ambil sk_cabang dari dim_cabang
        $sk_cabang = DB::table('dim_cabang')
            ->where('cabang_id', $booking->cabang_id)
            ->value('sk_cabang');

        // Ambil sk_waktu dari dim_waktu berdasarkan tanggal_booking
        $sk_waktu = DB::table('dim_waktu')
            ->where('tanggal_booking', $booking->tanggal_booking)
            ->value('sk_waktu');

        DB::connection('warehouse')->table('fact_pembayaran')
            ->updateOrInsert(
                ['sk_pembayaran' => $p->pembayaran_id],
                [
                    'sk_waktu' => $sk_waktu,
                    'sk_cabang' => $sk_cabang,
                    'sk_booking' => $p->booking_id,
                    'pembayaran_id' => $p->pembayaran_id,
                    'jumlah' => $p->jumlah,
                    'metode_bayar' => $p->metode_bayar,
                    'status' => $p->status,
                    'tanggal_bayar' => $p->tanggal_bayar
                ]
            );
    }

    $this->info("fact_pembayaran loaded with booking mapping");
    }

    private function loadFactBooking()
    {
    $bookings = DB::table('booking')->get();

    foreach ($bookings as $b) {
        // Ambil sk_waktu dari dim_waktu berdasarkan tanggal_booking
        $sk_waktu = DB::table('dim_waktu')
            ->where('tanggal_booking', $b->tanggal_booking)
            ->value('sk_waktu');

        // Ambil sk_cabang dari dim_cabang
        $sk_cabang = DB::table('dim_cabang')
            ->where('cabang_id', $b->cabang_id)
            ->value('sk_cabang');

        DB::connection('warehouse')->table('fact_booking')
            ->updateOrInsert(
                ['sk_booking_fact' => $b->booking_id],
                [
                    'sk_waktu' => $sk_waktu,
                    'sk_booking' => $b->booking_id,
                    'sk_cabang' => $sk_cabang,
                    'status_tes' => $b->status_tes,
                    'status_pembayaran' => $b->status_pembayaran
                ]
            );
    }

    $this->info("fact_booking loaded");
    }

}

