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
        $siswaOrtuQuery = Siswa::query()->where('user_id', Auth::user()->id)->get();

        $today = \Carbon\Carbon::now()->locale('id')->dayName;
        return Inertia::render('Dashboard', [
            'historyQuery' => History::query()->with('user')->orderBy('created_at', 'desc')->get(),
            'guruQuery' => Guru::query()->orderBy('nama', 'asc')->orderBy('nip', 'asc')->get()->map(function ($guru) {
                $guru['label'] = $guru->nama. ' (' .$guru->nip . ')';
                return $guru;
            }),
            'guruPiketQuery' => Guru::query()->with('user')->where('piket', $today)->get(),
            'siswaQuery' => Siswa::query()->orderBy('nama', 'asc')->orderBy('nis', 'asc')->get()->map(function ($siswa) {
                $siswa['label'] = $siswa->nama. ' (' .$siswa->nis . ')';
                return $siswa;
            }),
            'mataPelajaranQuery' => MataPelajaran::query()->orderBy('nama', 'asc')->orderBy('kelas', 'asc')->get()->map(function ($mataPelajaran) {
                $mataPelajaran['label'] = $mataPelajaran->nama. ' (Kelas ' .$mataPelajaran->kelas. ')';
                return $mataPelajaran;
            }),
            'SOPQuery' => SOP::query()->orderBy('kategori', 'asc')->get()->map(function ($sop) {
                $sop['label'] = $sop->kategori;
                return $sop;
            }),
            'pelanggaranQuery' => Pelanggaran::query()->get(),
            'pelanggaranSiswaQuery' => Pelanggaran::query()->with('siswa')->where('siswa_id', $siswaOrtuQuery->first()->id ?? '')->get() ?? null,
            'userQuery' => User::query()->get(),
            'can' => [
                'viewUser' => Auth::user()->can('viewAny', User::class),
                'viewSiswa' => Auth::user()->can('viewAny', Siswa:: class),
                'viewGuru' => Auth::user()->can('viewAny', Guru:: class),
                'viewMataPelajaran' => Auth::user()->can('viewAny', MataPelajaran:: class),
                'viewSOP' => Auth::user()->can('viewAny', SOP:: class),
                'viewPelanggaran' => Auth::user()->can('viewAny', Pelanggaran::class),
                'createPelanggaran' => Auth::user()->can('create', Pelanggaran::class),
            ],
        ]);
    }
}
