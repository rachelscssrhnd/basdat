<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'pembayaran';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'pembayaran_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'booking_id',
        'jumlah',
        'metode_bayar',
        'status',
        'tanggal_bayar',
        'bukti_path',
        'bukti_pembayaran',
        'tanggal_upload',
        'tanggal_konfirmasi',
        'alasan_reject',
    ];

    /**
     * Relasi "belongsTo": Setiap pembayaran terhubung ke satu Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}