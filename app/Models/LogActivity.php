<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = 'log_activity';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'log_activity';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'log_id';

    /**
     * Menonaktifkan pengelolaan otomatis timestamp oleh Eloquent.
     * Di-set false karena tabel hanya memiliki 'created_at'.
     */
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
    ];

    /**
     * Relasi "belongsTo": Setiap log aktivitas dilakukan oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}