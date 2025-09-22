<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTesValue extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'hasil_tes_value';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'hasil_value_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'hasil_id',
        'param_id',
        'nilai_hasil',
    ];

    /**
     * Relasi "belongsTo": Setiap nilai hasil adalah bagian dari satu Header Hasil Tes.
     */
    public function header()
    {
        return $this->belongsTo(HasilTesHeader::class, 'hasil_id', 'hasil_id');
    }

    /**
     * Relasi "belongsTo": Setiap nilai hasil mengacu pada satu Parameter Tes.
     */
    public function parameter()
    {
        return $this->belongsTo(ParameterTes::class, 'param_id', 'param_id');
    }
}