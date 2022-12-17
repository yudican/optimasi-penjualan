<?php

namespace App\Http\Livewire\Master;

use App\Models\DataProduk;
use Livewire\Component;
use App\Imports\DataProductImport;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class DataProdukController extends Component
{
    use WithFileUploads;
    public $tbl_data_produk_id;
    public $nama_produk;
    public $harga_produk;
    public $deskripsi_produk;
    public $garansi_produk;
    public $file;
    public $file_path;



    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataDataProdukById', 'getDataProdukId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.master.tbl-data-produk', [
            'items' => DataProduk::all()
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'id' => strtotime(date('Y-m-d H:i:s')),
            'nama_produk'  => $this->nama_produk,
            'harga_produk'  => $this->harga_produk,
            'deskripsi_produk'  => $this->deskripsi_produk,
            'garansi_produk'  => $this->garansi_produk
        ];

        DataProduk::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'nama_produk'  => $this->nama_produk,
            'harga_produk'  => $this->harga_produk,
            'deskripsi_produk'  => $this->deskripsi_produk,
            'garansi_produk'  => $this->garansi_produk
        ];
        $row = DataProduk::find($this->tbl_data_produk_id);



        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        DataProduk::find($this->tbl_data_produk_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_produk'  => 'required',
            'harga_produk'  => 'required',
            'deskripsi_produk'  => 'required',
            'garansi_produk'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataDataProdukById($tbl_data_produk_id)
    {
        $this->_reset();
        $row = DataProduk::find($tbl_data_produk_id);
        $this->tbl_data_produk_id = $row->id;
        $this->nama_produk = $row->nama_produk;
        $this->harga_produk = $row->harga_produk;
        $this->deskripsi_produk = $row->deskripsi_produk;
        $this->garansi_produk = $row->garansi_produk;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataProdukId($tbl_data_produk_id)
    {
        $row = DataProduk::find($tbl_data_produk_id);
        $this->tbl_data_produk_id = $row->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function saveImport()
    {
        Excel::import(new DataProductImport, $this->file_path);
        $this->_reset();
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->emit('showModalImport','hide');
        $this->tbl_data_produk_id = null;
        $this->nama_produk = null;
        $this->harga_produk = null;
        $this->deskripsi_produk = null;
        $this->garansi_produk = null;
        $this->file = null;
        $this->file_path = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
