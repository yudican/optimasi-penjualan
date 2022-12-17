<?php

namespace App\Http\Livewire\Transaksi;

use App\Models\DataProduk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataTransaksiImport;

class TransaksiController extends Component
{
    use WithFileUploads;
    public $tbl_transaksi_id;
    public $kode_transaksi;
    public $tanggal_transaksi;
    public $data_produk_id = [];

    public $file;
    public $file_path;

    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataTransaksiById', 'getTransaksiId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.transaksi.tbl-transaksi', [
            'items' => Transaksi::all(),
            'produks' => DataProduk::all()
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'kode_transaksi'  => $this->kode_transaksi,
            'tanggal_transaksi'  => $this->tanggal_transaksi
        ];

        $transaksi = Transaksi::create($data);
        $produk_id = $this->data_produk_id;
        if (is_array($produk_id)) {
            $products = [];
            foreach ($produk_id as $produk) {
                $products[] = [
                    'produk_id' => $produk,
                    'transaksi_id' => $transaksi->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            TransaksiDetail::insert($products);
        }

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'kode_transaksi'  => $this->kode_transaksi,
            'tanggal_transaksi'  => $this->tanggal_transaksi
        ];
        $row = Transaksi::find($this->tbl_transaksi_id);
        $row->transaksiDetail()->delete();
        $products = [];
        $produk_id = $this->data_produk_id;
        if (is_array($produk_id)) {
            $products = [];
            foreach ($produk_id as $produk) {
                $products[] = [
                    'produk_id' => $produk,
                    'transaksi_id' => $row->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            TransaksiDetail::insert($products);
        }


        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Transaksi::find($this->tbl_transaksi_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'kode_transaksi'  => 'required',
            'tanggal_transaksi'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataTransaksiById($tbl_transaksi_id)
    {
        $this->_reset();
        $row = Transaksi::find($tbl_transaksi_id);
        $this->tbl_transaksi_id = $row->id;
        $this->kode_transaksi = $row->kode_transaksi;
        $this->tanggal_transaksi = $row->tanggal_transaksi;
        $this->data_produk_id = $row->transaksiDetail()->pluck('produk_id')->toArray();
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getTransaksiId($tbl_transaksi_id)
    {
        $row = Transaksi::find($tbl_transaksi_id);
        $this->tbl_transaksi_id = $row->id;
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
        $this->kode_transaksi = $this->_getTransactionCode();
        $this->tanggal_transaksi = date('Y-m-d');
    }

    public function saveImport()
    {
        Excel::import(new DataTransaksiImport, $this->file_path);
        $this->_reset();
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->emit('showModalImport','hide');
        $this->tbl_transaksi_id = null;
        $this->kode_transaksi = null;
        $this->tanggal_transaksi = null;
        $this->file = null;
        $this->file_path = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
        $this->data_produk_id = [];
    }

    public function _getTransactionCode($label = 'TRX', $prefix = '-')
    {
        $tanggal = date('dm') . substr(date('Y'), 2);
        $kode = '0001';

        $transaksi = Transaksi::latest()->first();

        if ($transaksi) {
            $kode_transaksi = explode($prefix, $transaksi->kode_transaksi);
            $bulan = substr($kode_transaksi[1], 2, -2);
            if (date('m') == $bulan) {
                $final_kode = $label . $prefix . $tanggal . $prefix;
                $kode_transaksi = $kode_transaksi[2] + 1;

                $final_kode = $final_kode . sprintf("%04s", $kode_transaksi);
                return $final_kode;
            }

            $final_kode = $label . $prefix . $tanggal . $prefix . $kode;
            return $final_kode;
        }

        $final_kode = $label . $prefix . $tanggal . $prefix . $kode;
        return $final_kode;
    }
}
