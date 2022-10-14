<?php

namespace App\Http\Livewire;

use App\Models\Pengaturan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Livewire\Component;
use Phpml\Association\Apriori;

class ProsesData extends Component
{
    public $data_items = [];
    public $data_item_rules = [];
    public function mount()
    {
        $data_transaksi = [];
        $transaksis = Transaksi::all();
        foreach ($transaksis as $transaksi) {
            $data_transaksi[] = $transaksi->transaksiDetail()->get()->pluck('dataProduk.nama_produk')->toArray();
        }

        $support = (float) Pengaturan::where('code', 'SUPPORT')->first()->value;
        $confidence = (float) Pengaturan::where('code', 'CONFIDEN')->first()->value;

        $labels  = [];
        $associator = new Apriori($support, $confidence);
        $associator->train($data_transaksi, $labels);

        $aprioris = [];
        foreach ($associator->apriori() as $key => $apriori) {
            foreach ($apriori as $keys => $apr) {
                $qty = count(array_filter($data_transaksi, function ($entry) use ($apr) {
                    return !array_diff($apr, array_intersect($apr, $entry));
                }));
                $aprioris[$key][] = [
                    'label' => $apr,
                    'qty' => $qty,
                    'suport' => $qty / count($data_transaksi)
                ];
            }
        }
        $this->data_items = $aprioris;
        $this->data_item_rules = $this->unique_multidim_array($associator->getRules(), 'confidence');
        // // dd($associator->predict(['Apel']));
        // dd($associator->getRules());
        // dd($associator->apriori());
        // dd($data_transaksi);

    }
    public function render()
    {
        return view('livewire.proses-data');
    }

    // unique array by value
    public function unique_multidim_array($array, $key)
    {
        $temp_array = [];
        $i = 0;
        $key_array = [];

        foreach ($array as $keys => $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }

        $newData = [];
        $no = 1;
        foreach ($temp_array as $index => $value) {
            $newData[$index] = $value;
            $newData[$index]['no'] = $no;
            $no++;
        }

        return $newData;
    }
}
