<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class DataTransaksiImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $years = [2017,2018,2019,2020,2021,2022];
        $data = [
            'id'  => $row['transaction_id'],
            'kode_transaksi'  => 'TRX-'.$years[rand(0,5)].'-'.rand(1,12).'-'.rand(1,31),
            'tanggal_transaksi'  => date('Y-m-d H:i:s')
        ];

        Transaksi::updateOrCreate(['id'  => $row['transaction_id']],$data);
       
        TransaksiDetail::updateOrCreate([
            'produk_id' => $row['product_id'],
            'transaksi_id' => $row['transaction_id']
        ]);
    }

    //  public function headingRow(): int
    // {
    //     return 2;
    // }
}
