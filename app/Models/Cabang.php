<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'cabang';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'cabang_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'nama_cabang',
        'alamat',
    ];

    /**
     * Relasi "hasMany": Satu cabang bisa memiliki banyak Booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'cabang_id', 'cabang_id');
    }

    /**
     * Relasi "hasMany": Satu cabang bisa memiliki banyak Dokter.
     */
    public function dokters()
    {
        return $this->hasMany(Dokter::class, 'cabang_id', 'cabang_id');
    }

    /**
     * Relasi "hasMany": Satu cabang bisa memiliki banyak Staf.
     */
    public function stafs()
    {
        return $this->hasMany(Staf::class, 'cabang_id', 'cabang_id');
    }
}