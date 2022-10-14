<?php

namespace App\Http\Livewire\Setting;

use App\Models\Pengaturan;
use Livewire\Component;


class PengaturanController extends Component
{
    
    public $tbl_pengaturan_id;
    public $code;
public $value;
    
   

    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataPengaturanById', 'getPengaturanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.setting.tbl-pengaturan', [
            'items' => Pengaturan::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        
        $data = ['code'  => $this->code,
'value'  => $this->value];

        Pengaturan::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = ['code'  => $this->code,
'value'  => $this->value];
        $row = Pengaturan::find($this->tbl_pengaturan_id);

        

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Pengaturan::find($this->tbl_pengaturan_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'code'  => 'required',
'value'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataPengaturanById($tbl_pengaturan_id)
    {
        $this->_reset();
        $row = Pengaturan::find($tbl_pengaturan_id);
        $this->tbl_pengaturan_id = $row->id;
        $this->code = $row->code;
$this->value = $row->value;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getPengaturanId($tbl_pengaturan_id)
    {
        $row = Pengaturan::find($tbl_pengaturan_id);
        $this->tbl_pengaturan_id = $row->id;
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

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->tbl_pengaturan_id = null;
        $this->code = null;
$this->value = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
