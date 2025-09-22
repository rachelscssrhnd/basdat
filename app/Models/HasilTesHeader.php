<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTesHeader extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'hasil_tes_header';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'hasil_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'booking_id',
        'dibuat_oleh',
        'tanggal_input',
    ];

    /**
     * Relasi "belongsTo": Setiap header hasil tes terhubung ke satu Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Relasi "belongsTo": Setiap header hasil tes dibuat oleh satu User (Staf/Admin).
     * Kita perlu menentukan foreign key 'dibuat_oleh' secara eksplisit.
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }

    /**
     * Relasi "hasMany": Satu header hasil tes memiliki banyak nilai/value detail.
     */
    public function detailHasil()
    {
        return $this->hasMany(HasilTesValue::class, 'hasil_id', 'hasil_id');
    }
}