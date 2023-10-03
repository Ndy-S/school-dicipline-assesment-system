<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guru;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function guru() {
        return $this->belongsTo(Guru::class);
    }
}
