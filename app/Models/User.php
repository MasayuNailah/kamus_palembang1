<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_user',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relasi dengan Kata yang diinput
    public function kataKontribusi(): HasMany
    {
        return $this->hasMany(Kata::class, 'id_kontributor', 'id_user');
    }

    // Relasi dengan Kata yang divalidasi
    public function kataValidasi(): HasMany
    {
        return $this->hasMany(Kata::class, 'id_validator', 'id_user');
    }

    // Relasi dengan Kalimat yang diinput
    public function kalimatKontribusi(): HasMany
    {
        return $this->hasMany(Kalimat::class, 'id_kontributor', 'id_user');
    }

    // Relasi dengan Kalimat yang divalidasi
    public function kalimatValidasi(): HasMany
    {
        return $this->hasMany(Kalimat::class, 'id_validator', 'id_user');
    }
    
    // Relasi dengan Transaksi Validasi yang diinput
    public function transaksiKontribusi(): HasMany
    {
        return $this->hasMany(TransaksiValidasi::class, 'id_kontributor', 'id_user');
    }
    
    // Relasi dengan Transaksi Validasi yang divalidasi
    public function transaksiValidasi(): HasMany
    {
        return $this->hasMany(TransaksiValidasi::class, 'id_validator', 'id_user');
    }
}