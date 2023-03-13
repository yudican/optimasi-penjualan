<?php

namespace App\Http\Livewire;

use App\Models\Transaksi;
use Livewire\Component;

class Dashboard extends Component
{
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
