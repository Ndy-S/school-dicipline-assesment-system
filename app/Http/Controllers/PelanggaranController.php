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
use Illuminate\Support\Facades\Auth;

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
            'pelanggaranPaginate' => $pelanggaranQuery->with('siswa')->with('s_o_p')->with('guru')->with('mata_pelajaran')->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
            'historyQuery' => History::query()->where('nama_tabel', 'data pelanggaran')->with('user')->orderBy('created_at', 'desc')->get(),
            'guruQuery' => Guru::query()->orderBy('nama', 'asc')->orderBy('nip', 'asc')->get()->map(function ($guru) {
                $guru['label'] = $guru->nama. ' (' .$guru->nip . ')';
                return $guru;
            }),
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
        ]);
    }

    public function dataProcess($request) {
        try {
            $attributes = $request->validate([
                'siswa_id' => 'required',
                's_o_p_id' => 'required',
                'guru_id' => 'required',
                'jenis' => 'required'
            ]);

            $nullableFields = [
                'id',
                'deskripsi',
                'sanksi',
                'mata_pelajaran_id',
                'bukti_path'
            ];

            foreach($nullableFields as $field) {
                if ($field == 'mata_pelajaran_id' && is_string($request->input($field))) {
                    continue;
                } else if ($field == 'mata_pelajaran_id' && !is_null($request->input($field))) {
                    $attributes = array_merge($attributes, [$field => $request->input($field)['id']]);
                } else if (!is_null($request->input($field))) {
                    $attributes = array_merge($attributes, [$field => $request->input($field)]);
                } 
            }

            return $attributes;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request) {
        $request->validate([
            'siswa_id' => 'required',
            's_o_p_id' => 'required',
            'guru_id' => 'required',
            'jenis' => 'required'
        ]);

        $attributes = $this->dataProcess($request);

        $history = History::create([
            'user_id' => Auth::id(),
            'nama_tabel' => 'data pelanggaran',
            'jenis' => 'tambah',
            'nama_data' => $attributes['siswa_id']['nama'],
            'token_data' => $attributes['s_o_p_id']['kategori'],
        ]);

        $attributes['siswa_id'] = $attributes['siswa_id']['id'];
        $attributes['s_o_p_id'] = $attributes['s_o_p_id']['id'];
        $attributes['guru_id'] = $attributes['guru_id']['id'];

        $pelanggaran = Pelanggaran::create($attributes);

        return back()->withInput();
    }

    public function update(Request $request) {
        $request->validate([
            'siswa_id' => 'required',
            's_o_p_id' => 'required',
            'guru_id' => 'required',
            'jenis' => 'required'
        ]);

        $attributes = $this->dataProcess($request);
        foreach ($attributes as $field => $value) {
            if (($field == 'siswa_id' || $field == 's_o_p_id' || $field == 'guru_id') && is_string($value)) {
                unset($attributes[$field]);
            } else if (!is_null($value) && ($field == 'siswa_id' || $field == 's_o_p_id' || $field == 'guru_id')) {
                $attributes[$field] = $value['id'];
            } else if ($field == 'jenis' && $value == 'Sekolah') {
                $attributes['mata_pelajaran_id'] = null;
            }
        }
        
        // Might be changed
        $pelanggaran = Pelanggaran::with('siswa')->with('s_o_p')->findOrFail($attributes['id']);
        $pelanggaran->update($attributes);
        $history = History::create([
            'user_id' => Auth::id(),
            'nama_tabel' => 'data pelanggaran',
            'jenis' => 'ubah',
            'nama_data' => $pelanggaran->siswa->nama,
            'token_data' => $pelanggaran->s_o_p->kategori,
        ]);

        return back()->withInput();
    }
    
    public function destroy(Request $request) {
        try {
            $pelanggaran = Pelanggaran::findOrFail($request->id);

            Pelanggaran::destroy($pelanggaran->id);

            $pelanggaran->with('siswa')->with('s_o_p')->get();

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data pelanggaran',
                'jenis' => 'hapus',
                'nama_data' => $pelanggaran->siswa->nama,
                'token_data' => $pelanggaran->s_o_p->kategori,
            ]);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
