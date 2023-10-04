<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\History;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class MataPelajaranController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:nama,kelas']
        ]);

        $mataPelajaranQuery = MataPelajaran::query();

        if (request('search')) {
            $mataPelajaranQuery
                ->where('nama', 'LIKE', '%'.request('search').'%')
                ->orWhere('kelas', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $mataPelajaranQuery->orderBy(request('field'), request('direction'));
        }
        
        return Inertia::render('MataPelajaran', [
            'mataPelajaranQuery' => $mataPelajaranQuery->get(),
            'mataPelajaranPaginate' => $mataPelajaranQuery->with('guru')->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
            'historyQuery' => History::query()->where('nama_tabel', 'data mata pelajaran')->with('user')->orderBy('created_at', 'desc')->get(),
            'guruQuery' => Guru::query()->orderBy('nama', 'asc')->orderBy('nip', 'asc')->get()->map(function ($guru) {
                $guru['label'] = $guru->nama. ' (' .$guru->nip . ')';
                return $guru;
            }),
        ]);
    }

    public function dataProcess($request) {
        try {
            $attributes = $request->validate([
                'nama' => 'required',
                'kelas' => 'required',
            ]);

            $nullableFields = [
                'id',
                'guru_id'
            ];

            foreach($nullableFields as $field) {
                if ($field == 'guru_id' && is_string($request->input($field))) {
                    continue;
                } else if ($field == 'guru_id' && !is_null($request->input($field))) {
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
        try {
            $attributes = $this->dataProcess($request);

            $mataPelajaran = MataPelajaran::create($attributes);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data mata pelajaran',
                'jenis' => 'tambah',
                'nama_data' => $attributes['nama'],
                'token_data' => 'Kelas ' .$attributes['kelas'],
            ]);

            return back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {
        try {
            $attributes = $this->dataProcess($request);

            $mataPelajaran = MataPelajaran::findOrFail($attributes['id']);

            $mataPelajaran->update($attributes);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data mata pelajaran',
                'jenis' => 'ubah',
                'nama_data' => $attributes['nama'],
                'token_data' => 'Kelas ' .$attributes['kelas'],
            ]);

            return back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request) {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($request->id);

            MataPelajaran::destroy($mataPelajaran->id);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data mata pelajaran',
                'jenis' => 'hapus',
                'nama_data' => $mataPelajaran->nama,
                'token_data' => 'Kelas ' .$mataPelajaran->kelas,
            ]);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
