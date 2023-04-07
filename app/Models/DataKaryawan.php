<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'data_karyawan';
    //public $incrementing = false;

    protected $fillable = ['pangkat', 'satker', 'tahun_masuk', 'user_id'];

    protected $dates = [];

    /**
     * Get the user that owns the DataKaryawan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
