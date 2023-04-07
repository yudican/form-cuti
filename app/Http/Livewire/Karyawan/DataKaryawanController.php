<?php

namespace App\Http\Livewire\Karyawan;

use App\Models\DataKaryawan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;


class DataKaryawanController extends Component
{

    public $data_karyawan_id;
    public $pangkat;
    public $satker;
    public $tahun_masuk;
    public $name;
    public $username;


    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataDataKaryawanById', 'getDataKaryawanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.karyawan.data-karyawan')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        try {
            DB::beginTransaction();
            $dataUser = [
                'name'  => $this->name,
                'username'  => $this->username,
                'email'  => $this->username . '@gmail.com',
                'password'  => Hash::make('NRP' . $this->username),

            ];

            $user = User::create($dataUser);
            $team = Team::find(1);
            $team->users()->attach($user, ['role' => 'member']);
            $user->roles()->attach('0feb7d3a-90c0-42b9-be3f-63757088cb9a');

            $data = [
                'pangkat'  => $this->pangkat,
                'satker'  => $this->satker,
                'tahun_masuk'  => $this->tahun_masuk,
                'user_id'  => $user->id
            ];

            DataKaryawan::create($data);
            DB::commit();
            $this->_reset();
            return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->_reset();
            return $this->emit('showAlertError', ['msg' => 'Data Gagal Disimpan']);
        }
    }

    public function update()
    {

        $this->_validate();
        try {
            DB::beginTransaction();

            $dataUser = [
                'name'  => $this->name,
                'username'  => $this->username,
                'email'  => $this->username . '@gmail.com',
                'password'  => 'NRP' . $this->username,

            ];

            $data = [
                'pangkat'  => $this->pangkat,
                'satker'  => $this->satker,
                'tahun_masuk'  => $this->tahun_masuk
            ];

            $row = DataKaryawan::find($this->data_karyawan_id);
            $row->update($data);
            $row->user()->update($dataUser);

            DB::commit();
            $this->_reset();
            return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->_reset();
            return $this->emit('showAlertError', ['msg' => 'Data Gagal Disimpan']);
        }
    }

    public function delete()
    {
        DataKaryawan::find($this->data_karyawan_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'pangkat'  => 'required',
            'satker'  => 'required',
            'tahun_masuk'  => 'required',
            'name'  => 'required',
            'username'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataDataKaryawanById($data_karyawan_id)
    {
        $this->_reset();
        $row = DataKaryawan::find($data_karyawan_id);
        $this->data_karyawan_id = $row->id;
        $this->pangkat = $row->pangkat;
        $this->satker = $row->satker;
        $this->tahun_masuk = date('Y-m-d', strtotime($row->tahun_masuk));
        $this->name = $row->user->name;
        $this->username = $row->user->username;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataKaryawanId($data_karyawan_id)
    {
        $row = DataKaryawan::find($data_karyawan_id);
        $this->data_karyawan_id = $row->id;
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
        $this->data_karyawan_id = null;
        $this->pangkat = null;
        $this->satker = null;
        $this->tahun_masuk = null;
        $this->name = null;
        $this->username = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
