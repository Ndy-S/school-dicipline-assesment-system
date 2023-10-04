<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Inertia\Inertia;
use App\Models\History;
use App\Models\MataPelajaran;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\SOP;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard() {
        return Inertia::render('Dashboard', [
            'historyQuery' => History::query()->with('user')->orderBy('created_at', 'desc')->get(),
            'can' => [
                'viewUser' => Auth::user()->can('viewAny', User::class),
                'viewSiswa' => Auth::user()->can('viewAny', Siswa:: class),
                'viewGuru' => Auth::user()->can('viewAny', Guru:: class),
                'viewMataPelajaran' => Auth::user()->can('viewAny', MataPelajaran:: class),
                'viewSOP' => Auth::user()->can('viewAny', SOP:: class),
                'viewPelanggaran' => Auth::user()->can('viewAny', Pelanggaran::class),
            ],
        ]);
    }
}
