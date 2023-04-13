<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataKaryawan;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class DataKaryawanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    // public $hideable = 'select';
    public $table_name = 'tbl_data_karyawan';

    public function builder()
    {
        return DataKaryawan::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('user.name')->label('Nama Anggota')->searchable(),
            Column::name('user.username')->label('NRP')->searchable(),
            Column::name('pangkat')->label('Pangkat')->searchable(),
            Column::name('satker')->label('Satker')->searchable(),
            Column::name('tahun_masuk')->label('Tahun Masuk')->searchable(),

            Column::callback(['id'], function ($id) {
                return view('crud-generator-components::action-button', [
                    'id' => $id,
                    'actions' => [
                        [
                            'type' => 'button',
                            'route' => 'getDataById(' . $id . ')',
                            'label' => 'Edit',
                        ],
                        [
                            'type' => 'button',
                            'route' => 'getId(' . $id . ')',
                            'label' => 'Hapus',
                        ]
                    ]
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataDataKaryawanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getDataKaryawanId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
