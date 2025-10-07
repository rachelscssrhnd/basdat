<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'user';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'user_id';

    /**
     * Tabel ini tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
    ];
    
    /**
     * Override default password column for authentication.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Relasi "belongsTo": Setiap user memiliki satu Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Relasi "hasOne": Akun user ini mungkin memiliki satu profil Pasien.
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'user_id', 'user_id');
    }

    /**
     * Relasi "hasOne": Akun user ini mungkin memiliki satu profil Staf.
     */
    public function staf()
    {
        return $this->hasOne(Staf::class, 'user_id', 'user_id');
    }
}