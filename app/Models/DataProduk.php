<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProduk extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'data_produk';
    public $incrementing = false;

    //public $incrementing = false;

    protected $fillable = ['id','nama_produk', 'harga_produk', 'deskripsi_produk', 'garansi_produk'];

    /**
     * Get all of the transaksiDetail for the DataProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
