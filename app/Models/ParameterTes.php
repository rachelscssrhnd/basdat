<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterTes extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'parameter_tes';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'param_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'tes_id',
        'nama_parameter',
        'satuan',
    ];

    /**
     * Relasi Many-to-Many ke model JenisTes melalui tabel pivot 'detail_tes'.
     */
    public function jenisTes()
    {
        return $this->belongsTo(JenisTes::class, 'tes_id', 'tes_id');
    }

    /**
     * Relasi "hasMany": Satu parameter bisa muncul di banyak baris detail hasil tes.
     */
    public function nilaiHasil()
    {
        return $this->hasMany(HasilTesValue::class, 'param_id', 'param_id');
    }
}