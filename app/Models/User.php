<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // HARUS ADA untuk createToken()

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username', // Pastikan 'username' ada di fillable
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        // Hapus 'email_verified_at' jika Anda tidak menggunakannya
        // 'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Jika Anda ingin menonaktifkan timestamps, Anda akan menambahkan baris ini:
    // public $timestamps = false;
    // Tapi karena Anda mendapat error, kita asumsikan Anda ingin mengaktifkannya.
    // Jika Anda TIDAK menambahkan baris di atas, Laravel akan secara otomatis mencari
    // kolom created_at dan updated_at.
}
