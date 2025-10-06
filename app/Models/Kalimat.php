<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kalimat extends Model
{
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'tb_kalimat';

    /**
     * Nama primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id_kalimat';

    /**
     * Tipe data primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Primary key is not auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'id_kalimat',
        'sub_id',
        'kalimat',
        'arti',
        'status_validasi',
        'id_kontributor',
        'id_validator',
    ];

    /**
     * Relasi dengan Kata.
     */
    public function kata(): BelongsTo
    {
        return $this->belongsTo(Kata::class, 'sub_id', 'sub_id');
    }

    /**
     * Relasi dengan Kontributor (User).
     */
    public function kontributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kontributor', 'id_user');
    }

    /**
     * Relasi dengan Validator (User).
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_validator', 'id_user');
    }
}