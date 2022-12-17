<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'transaksi';
    //public $incrementing = false;
    public $incrementing = false;
    protected $fillable = ['id','kode_transaksi', 'tanggal_transaksi'];

    protected $dates = [];

    /**
     * Get all of the transaksiDetail for the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
