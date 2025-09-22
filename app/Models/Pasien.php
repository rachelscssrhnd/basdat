<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'pasien';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'pasien_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'nama',
        'tgl_lahir',
        'email',
        'no_hp',
        'user_id',
    ];

    /**
     * Relasi "belongsTo": Data pasien ini terhubung dengan satu akun User (untuk login).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi "hasMany": Satu pasien bisa memiliki banyak histori Booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'pasien_id', 'pasien_id');
    }
}