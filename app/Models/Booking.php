<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'booking';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'booking_id';

    /**
     * Menonaktifkan pengelolaan otomatis timestamp (created_at & updated_at) oleh Eloquent.
     * Di-set false karena tabel hanya memiliki 'created_at' (diurus oleh DB).
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'pasien_id',
        'cabang_id',
        'tanggal_booking',
        'status_pembayaran',
        'status_tes',
    ];

    /**
     * Relasi "belongsTo": Setiap booking dimiliki oleh satu Pasien.
     * Dibuat berdasarkan foreign key 'pasien_id'.
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }

    /**
     * Relasi "belongsTo": Setiap booking berada di satu Cabang.
     * Dibuat berdasarkan foreign key 'cabang_id'.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'cabang_id');
    }
    
    /**
     * Relasi "hasOne": Setiap booking memiliki satu data Pembayaran.
     * Dibuat berdasarkan tabel 'pembayaran' yang memiliki 'booking_id'.
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'booking_id', 'booking_id');
    }
    
    /**
     * Relasi "hasMany": Setiap booking bisa memiliki banyak RiwayatBooking.
     */
    public function riwayatBooking()
    {
        return $this->hasMany(RiwayatBooking::class, 'booking_id', 'booking_id');
    }

    /**
     * Relasi "belongsToMany": Satu booking bisa memiliki banyak JenisTes.
     * Dibuat berdasarkan tabel pivot 'detail_booking'.
     */
    public function jenisTes()
    {
        return $this->belongsToMany(JenisTes::class, 'detail_booking', 'booking_id', 'tes_id');
    }
}