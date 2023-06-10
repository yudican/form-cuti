<?php

namespace App\Http\Livewire\Karyawan;

use App\Models\DataFormPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DataFormPengajuanController extends Component
{
    use WithFileUploads;
    public $data_form_pengajuan_id;
    public $file_jasmani;
    public $file_kesehatan;
    public $keterangan;
    public $status;
    public $tanggal_berangkat;
    public $tanggal_kembali;
    public $tujuan;
    public $user_id;
    public $file_jasmani_path;
    public $file_kesehatan_path;
    public $name;
    public $username;
    public $pangkat;
    public $nomor_sij;
    public $tanggal_disetujui;
    public $keperluan;
    public $pengikut;
    public $transportasi;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataDataFormPengajuanById', 'getDataFormPengajuanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
        if (auth()->user()->role->role_type === 'member') {
            $this->name = auth()->user()->name;
            $this->username = auth()->user()->username;
            $this->pangkat = auth()->user()->dataKaryawan?->pangkat;
        }
    }

    public function render()
    {
        return view('livewire.karyawan.data-form-pengajuan')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        // check start date and end date
        $tanggal_berangkat = date('Y-m-d', strtotime($this->tanggal_berangkat));
        $tanggal_kembali = date('Y-m-d', strtotime($this->tanggal_kembali));
        if ($tanggal_berangkat > $tanggal_kembali) {
            return $this->emit('showAlert', ['msg' => 'Tanggal Berangkat tidak boleh lebih besar dari tanggal kembali']);
        }
        $data = [
            'nomor_sij' => $this->generateSijNumber(),
            'keperluan'  => $this->keperluan,
            'pengikut'  => $this->pengikut,
            'transportasi'  => $this->transportasi,
            'keterangan'  => $this->keterangan,
            'status'  => $this->status,
            'tanggal_berangkat'  => $this->tanggal_berangkat,
            'tanggal_kembali'  => $this->tanggal_kembali,
            'tujuan'  => $this->tujuan,
            'user_id'  => auth()->user()->id,
            'status'  => 'draft',
        ];

        if ($this->file_jasmani_path) {
            $file_jasmani = $this->file_jasmani_path->store('upload', 'public');
            $data['file_jasmani'] = $file_jasmani;
        }

        if ($this->file_kesehatan_path) {
            $file_kesehatan = $this->file_kesehatan_path->store('upload', 'public');
            $data['file_kesehatan'] = $file_kesehatan;
        }

        DataFormPengajuan::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'keperluan'  => $this->keperluan,
            'pengikut'  => $this->pengikut,
            'transportasi'  => $this->transportasi,
            'keterangan'  => $this->keterangan,
            'status'  => $this->status,
            'tanggal_berangkat'  => $this->tanggal_berangkat,
            'tanggal_kembali'  => $this->tanggal_kembali,
            'tujuan'  => $this->tujuan,
            'user_id'  => $this->user_id
        ];
        $row = DataFormPengajuan::find($this->data_form_pengajuan_id);


        if ($this->file_jasmani_path) {
            $file_jasmani = $this->file_jasmani_path->store('upload', 'public');
            $data['file_jasmani'] = $file_jasmani;
            if (Storage::exists('public/' . $this->file_jasmani)) {
                Storage::delete('public/' . $this->file_jasmani);
            }
        }

        if ($this->file_kesehatan_path) {
            $file_kesehatan = $this->file_kesehatan_path->store('upload', 'public');
            $data['file_kesehatan'] = $file_kesehatan;
            if (Storage::exists('public/' . $this->file_kesehatan)) {
                Storage::delete('public/' . $this->file_kesehatan);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        DataFormPengajuan::find($this->data_form_pengajuan_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'tanggal_berangkat'  => 'required',
            'tanggal_kembali'  => 'required',
            'tujuan'  => 'required',
        ];

        return $this->validate($rule);
    }

    public function getDataDataFormPengajuanById($data_form_pengajuan_id)
    {
        $this->_reset();
        $row = DataFormPengajuan::find($data_form_pengajuan_id);
        $this->data_form_pengajuan_id = $row->id;
        $this->file_jasmani = $row->file_jasmani;
        $this->file_kesehatan = $row->file_kesehatan;
        $this->keterangan = $row->keterangan;
        $this->keterangan = $row->keterangan;
        $this->nomor_sij = $row->nomor_sij;
        $this->tanggal_disetujui = $row->tanggal_disetujui;
        $this->keperluan = $row->keperluan;
        $this->pengikut = $row->pengikut;
        $this->transportasi = $row->transportasi;
        $this->status = $row->status;
        $this->tanggal_berangkat = date('Y-m-d', strtotime($row->tanggal_berangkat));
        $this->tanggal_kembali = date('Y-m-d', strtotime($row->tanggal_kembali));
        $this->tujuan = $row->tujuan;
        $this->user_id = $row->user_id;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataFormPengajuanId($data_form_pengajuan_id)
    {
        $row = DataFormPengajuan::find($data_form_pengajuan_id);
        $this->data_form_pengajuan_id = $row->id;
        $this->data_form_pengajuan_id = $row->id;
        $this->file_jasmani = $row->file_jasmani_url;
        $this->file_kesehatan = $row->file_kesehatan_url;
        $this->keterangan = $row->keterangan;
        $this->keterangan = $row->keterangan;
        $this->nomor_sij = $row->nomor_sij;
        $this->tanggal_disetujui = $row->tanggal_disetujui;
        $this->keperluan = $row->keperluan;
        $this->pengikut = $row->pengikut;
        $this->transportasi = $row->transportasi;
        $this->status = $row->status;
        $this->tanggal_berangkat = date('d M Y', strtotime($row->tanggal_berangkat));
        $this->tanggal_kembali = date('d M Y', strtotime($row->tanggal_kembali));
        $this->tujuan = $row->tujuan;
        $this->user_id = $row->user_id;
        $this->name = $row->user_name;
        $this->username = $row->user->username;
        $this->pangkat = $row->user?->dataKaryawan?->pangkat;
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
        $this->data_form_pengajuan_id = null;
        $this->file_jasmani_path = null;
        $this->file_kesehatan_path = null;
        $this->file_jasmani = null;
        $this->file_kesehatan = null;
        $this->keterangan = null;
        $this->status = null;
        $this->tanggal_berangkat = null;
        $this->tanggal_kembali = null;
        $this->nomor_sij = null;
        $this->tanggal_disetujui = null;
        $this->keperluan = null;
        $this->pengikut = null;
        $this->transportasi = null;
        $this->tujuan = null;
        $this->user_id = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }

    public function generateSijNumber()
    {
        $latestSijNumber = DB::table('data_form_pengajuan')->whereNotNull('nomor_sij')->where('nomor_sij', 'like', 'SIJ/%')->orderBy('id', 'desc')->first();

        $currentYear = date('Y');
        $currentMonth = date('m');

        if (!$latestSijNumber) {
            $nextNumber = '001';
        } else {
            $latestNumber = explode('/', $latestSijNumber->nomor_sij);
            $latestMonth = $latestNumber[2];
            $latestYear = $latestNumber[3];

            if ($currentYear > $latestYear) {
                $nextNumber = '001';
            } elseif ($currentYear == $latestYear && $currentMonth > $latestMonth) {
                $nextNumber = '001';
            } else {
                $nextNumber = str_pad(intval($latestNumber[1]) + 1, 3, '0', STR_PAD_LEFT);
            }
        }

        $sijNumber = "SIJ/{$nextNumber}/{$currentMonth}/{$currentYear}";
        // save $sijNumber to the database or use it as needed
        return $sijNumber;
    }

    public function updateStatus($status)
    {
        if ($status === 'ditolak') {
            $this->validate([
                'keterangan' => 'required'
            ]);

            $data = [
                'status' => $status,
                'keterangan' => $this->keterangan
            ];
        }

        if ($status === 'disetujui') {
            $this->validate([
                'keterangan' => 'required'
            ]);

            $data = [
                'status' => $status,
                'keterangan' => $this->keterangan,
                'tanggal_disetujui' => date('Y-m-d')
            ];
        }

        if ($status === 'diusulkan') {
            $data = [
                'status' => $status,
            ];
        }


        DataFormPengajuan::find($this->data_form_pengajuan_id)->update($data);
        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }
}
