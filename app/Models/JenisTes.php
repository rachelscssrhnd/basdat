<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTes extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'jenis_tes';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'tes_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'nama_tes',
        'deskripsi',
        'harga',
        'persiapan_khusus',
    ];

    /**
     * Relasi Many-to-Many ke model Booking melalui tabel pivot 'detail_booking'.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'detail_booking', 'tes_id', 'booking_id');
    }

    /**
     * Relasi Many-to-Many ke model ParameterTes melalui tabel pivot 'detail_tes'.
     */
    public function parameterTes()
    {
        return $this->hasMany(ParameterTes::class, 'tes_id', 'tes_id');
    }
}