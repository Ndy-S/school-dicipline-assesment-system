<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\History;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\SOP;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:deskripsi,sanksi']
        ]);

        $pelanggaranQuery = Pelanggaran::query();

        if (request('search')) {
            $pelanggaranQuery
                ->where('deskripsi', 'LIKE', '%'.request('search').'%')
                ->orWhere('sanksi', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $pelanggaranQuery->orderBy(request('field'), request('direction'));
        }

        return Inertia::render('Pelanggaran', [
            'pelanggaranQuery' => $pelanggaranQuery->get(),
            'pelanggaranPaginate' => $pelanggaranQuery->with('siswa')->with('sop')->with('guru')->with('matapelajaran')->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
            'historyQuery' => History::query()->where('nama_tabel', 'data mata pelajaran')->with('user')->orderBy('created_at', 'desc')->get(),
            'guruQuery' => Guru::query()->get(),
            'siswaQuery' => Siswa::query()->get(),
            'mataPelajaranQuery' => MataPelajaran::query()->get(),
            'SOPQuery' => SOP::query()->get(),
        ]);
    }

    public function create(Request $request) {
        dd($request);
    }
}
