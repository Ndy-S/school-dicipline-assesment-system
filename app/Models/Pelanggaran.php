<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SOP;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function s_o_p() {
        return $this->belongsTo(SOP::class);
    }

    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }

    public function guru() {
        return $this->belongsTo(Guru::class);
    }

    public function mata_pelajaran() {
        return $this->belongsTo(MataPelajaran::class);
    }
}
