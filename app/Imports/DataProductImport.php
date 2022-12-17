<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\DataProduk;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataProductImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $data = [
            'id' => $row['product_id'],
            'nama_produk'  => $row['nama_barang'],
            'harga_produk'  => $row['price'],
            'deskripsi_produk'  => '-',
            'garansi_produk'  => '1 Tahun'
        ];

        DataProduk::updateOrCreate(['id' => $row['product_id']],$data);
    }

    //  public function headingRow(): int
    // {
    //     return 2;
    // }
}
