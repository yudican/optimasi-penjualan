<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Transaksi;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;
use App\Models\TransaksiDetail;

class TransaksiTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_transaksi';
    public $hide = [];

    public function builder()
    {
        return Transaksi::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('kode_transaksi')->label('Kode Transaksi')->searchable(),
            Column::name('tanggal_transaksi')->label('Tanggal Transaksi')->searchable(),

            Column::callback(['tbl_transaksi.id', 'tbl_transaksi.kode_transaksi'], function ($id, $kode) {
                $produks = TransaksiDetail::with('dataProduk')->where('transaksi_id', $id)->get()->pluck('dataProduk.nama_produk')->toArray();
                return implode(',', $produks);
            })->label(__('Barang')),
            Column::callback(['id'], function ($id) {
                return view('livewire.components.action-button', [
                    'id' => $id,
                    'segment' => $this->params
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataTransaksiById', $id);
    }

    public function getId($id)
    {
        $this->emit('getTransaksiId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function toggle($index)
    {
        if ($this->sort == $index) {
            $this->initialiseSort();
        }

        $column = HideableColumn::where([
            'table_name' => $this->table_name,
            'column_name' => $this->columns[$index]['name'],
            'index' => $index,
            'user_id' => auth()->user()->id
        ])->first();

        if (!$this->columns[$index]['hidden']) {
            unset($this->activeSelectFilters[$index]);
        }

        $this->columns[$index]['hidden'] = !$this->columns[$index]['hidden'];

        if (!$column) {
            HideableColumn::updateOrCreate([
                'table_name' => $this->table_name,
                'column_name' => $this->columns[$index]['name'],
                'index' => $index,
                'user_id' => auth()->user()->id
            ]);
        } else {
            $column->delete();
        }
    }
}
