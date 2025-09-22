<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'dokter';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'dokter_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'nama',
        'spesialisasi',
        'cabang_id',
    ];

    /**
     * Relasi "belongsTo": Setiap dokter bertugas di satu Cabang.
     * Dibuat berdasarkan foreign key 'cabang_id'.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'cabang_id');
    }
}