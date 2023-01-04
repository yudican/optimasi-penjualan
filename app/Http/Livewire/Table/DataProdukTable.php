<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataProduk;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class DataProdukTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable', 'hapusDataProdukMultiple'];
    public $hideable = 'select';
    public $table_name = 'tbl_data_produk';
    public $hide = [];

    public function builder()
    {
        return DataProduk::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::checkbox(),
            Column::name('id')->label('No.'),
            Column::name('nama_produk')->label('Nama Produk')->searchable(),
            Column::name('harga_produk')->label('Harga Produk')->searchable(),
            Column::name('deskripsi_produk')->label('Deskripsi Produk')->searchable(),
            Column::name('garansi_produk')->label('Garansi Produk')->searchable(),

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
        $this->emit('getDataDataProdukById', $id);
    }

    public function hapusDataProdukMultiple()
    {
        if (count($this->selected) > 0) {
            DataProduk::whereIn('id', $this->selected)->delete();
            $this->emit('refreshTable');
            return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
        }
        return $this->emit('showAlertError', ['msg' => 'Pilih Data Terlebih Dahulu']);
    }

    public function getId($id)
    {
        $this->emit('getDataProdukId', $id);
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
