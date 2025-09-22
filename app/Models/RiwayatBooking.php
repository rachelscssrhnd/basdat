<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBooking extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'riwayat_booking';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'history_id';

    /**
     * Menonaktifkan pengelolaan otomatis timestamp oleh Eloquent.
     * Tabel ini memiliki 'changed_at', bukan created_at/updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'booking_id',
        'previous_status',
        'new_status',
        'changed_by',
    ];

    /**
     * Relasi "belongsTo": Setiap riwayat adalah bagian dari satu Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Relasi "belongsTo": Setiap perubahan dilakukan oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by', 'user_id');
    }
}