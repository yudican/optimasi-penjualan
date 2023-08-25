<?php

namespace App\Http\Livewire;

use App\Models\Transaksi;
use Livewire\Component;

class Dashboard extends Component
{
    public $abjads = [
        1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E',
        6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J',
        11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O',
        16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T',
        21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z',
    ];

    public function render()
    {
        $transactions = Transaksi::all();
        $data = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->transaksiDetail as $key => $value) {
                $data[$value->dataProduk->nama_produk][] = $value->dataProduk->nama_produk;
            }
        }
        $charts = [];
        foreach ($data as $key => $value) {
            $charts[] = [
                'name' => $key,
                'data' => count($value)
            ];
        }

        $labels = [];
        $values = [];
        foreach ($charts as $key => $value) {
            $labels[] = $value['name'];
            $values[] = $value['data'];
        }

        $chartData = [
            'labels' => $labels,
            'values' => $values
        ];

        return view('livewire.dashboard', ['chartData' => $chartData]);
    }
}
