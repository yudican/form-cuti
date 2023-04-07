<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFormPengajuan extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'data_form_pengajuan';
    //public $incrementing = false;

    protected $fillable = ['nomor_sij', 'file_jasmani', 'file_kesehatan', 'keterangan', 'status', 'tanggal_berangkat', 'tanggal_kembali', 'tanggal_disetujui', 'tujuan', 'keperluan', 'transportasi', 'pengikut', 'user_id'];

    protected $dates = ['tanggal_berangkat', 'tanggal_kembali', 'tanggal_disetujui'];

    protected $appends = ['user_name', 'pangkat', 'file_jasmani_url', 'file_kesehatan_url'];

    /**
     * Get the user that owns the DataFormPengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserNameAttribute()
    {
        $user = User::find($this->user_id);
        return $user?->name ?? '-';
    }

    public function getPangkatAttribute()
    {
        $data_karyawan = DataKaryawan::where('user_id', $this->user_id)->first();
        if ($data_karyawan) {
            return $data_karyawan->pangkat . '/' . $data_karyawan->satker . '/' . $data_karyawan->user->user_name;
        }
        return '-';
    }

    public function getFileJasmaniUrlAttribute()
    {
        return $this->file_jasmani ? asset('storage/' . $this->file_jasmani) : '#';
    }

    public function getFileKesehatanUrlAttribute()
    {
        return $this->file_kesehatan ? asset('storage/' . $this->file_kesehatan) : '#';
    }
}
