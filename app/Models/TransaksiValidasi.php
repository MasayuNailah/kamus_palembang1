<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiValidasi extends Model
{
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'transaksi_validasi';

    /**
     * Nama primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id_transaksi';

    /**
     * Atribut yang tidak bisa diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $guarded = ['id_transaksi'];

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