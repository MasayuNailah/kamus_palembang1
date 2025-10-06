<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kata extends Model
{
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'tb_kata';

    /**
     * Nama primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id_kata';

    /**
     * Tipe data primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Atribut yang tidak bisa diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $guarded = ['id_kata'];

    // Relasi dengan Kata Dasar (Self-referencing relationship)
    public function kataDasar(): BelongsTo
    {
        return $this->belongsTo(Kata::class, 'sub_id', 'id_kata');
    }

    // Relasi dengan Kata Turunan
    public function kataTurunan(): HasMany
    {
        return $this->hasMany(Kata::class, 'sub_id', 'id_kata');
    }

    // Relasi dengan Kalimat
    public function kalimat(): HasMany
    {
        return $this->hasMany(Kalimat::class, 'sub_id', 'sub_id');
    }

    // Relasi dengan Kontributor (User)
    public function kontributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kontributor', 'id_user');
    }

    // Relasi dengan Validator (User)
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_validator', 'id_user');
    }
}