<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use App\Models\History;
use App\Models\MataPelajaran;
use App\Models\Pelanggaran;
use App\Models\SOP;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SiswaController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:nama,nis,kelas,gender,agama,alamat,handphone']
        ]);

        $siswaQuery = Siswa::query();

        if (request('search')) {
            $siswaQuery
                ->where('nama', 'LIKE', '%'.request('search').'%')
                ->orWhere('nis', 'LIKE', '%'.request('search').'%')
                ->orWhere('kelas', 'LIKE', '%'.request('search').'%')
                ->orWhere('gender', 'LIKE', '%'.request('search').'%')
                ->orWhere('agama', 'LIKE', '%'.request('search').'%')
                ->orWhere('alamat', 'LIKE', '%'.request('search').'%')
                ->orWhere('handphone', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $siswaQuery->orderBy(request('field'), request('direction'));
        }

        return Inertia::render('Siswa', [
            'siswaQuery' => $siswaQuery->get(),
            'siswaPaginate' => $siswaQuery->with('user')->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
            'historyQuery' => History::query()->where('nama_tabel', 'data siswa')->with('user')->orderBy('created_at', 'desc')->get(),
            'userQuery' => User::query()->where('peran', 'Orang Tua')->orderBy('nama', 'asc')->orderBy('token', 'asc')->get()->map(function ($user) {
                $user['label'] = $user->nama. ' (' .$user->token . ')';
                return $user;
            }),
            'can' => [
                'viewUser' => Auth::user()->can('viewAny', User::class),
                'viewSiswa' => Auth::user()->can('viewAny', Siswa:: class),
                'viewGuru' => Auth::user()->can('viewAny', Guru:: class),
                'viewMataPelajaran' => Auth::user()->can('viewAny', MataPelajaran:: class),
                'viewSOP' => Auth::user()->can('viewAny', SOP:: class),
                'viewPelanggaran' => Auth::user()->can('viewAny', Pelanggaran::class),
                'createSiswa' => Auth::user()->can('create', Siswa::class),
            ]
        ]);
    }

    public function dataProcess($request) {
        try {
            $attributes = $request->validate([
                'nama' => 'required',
                'nis' => 'required',
                'kelas' => 'required',
                'gender' => 'required',
            ]);

            $nullableFields = [
                'agama',
                'alamat',
                'handphone',
                'id',
                'user_id'
            ];

            foreach($nullableFields as $field) {
                if ($field == 'user_id' && is_string($request->input($field))) {
                    continue;
                } else if ($field == 'user_id' && !is_null($request->input($field))) {
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
        $attributes = $this->dataProcess($request);

        $siswa = Siswa::create($attributes);

        $history = History::create([
            'user_id' => Auth::id(),
            'nama_tabel' => 'data siswa',
            'jenis' => 'tambah',
            'nama_data' => $attributes['nama'],
            'token_data' => $attributes['nis'],
        ]);

        return back()->withInput();
    }

    public function update(Request $request) {
        $attributes = $this->dataProcess($request);

        $siswa = Siswa::findOrFail($attributes['id']);
        $siswa->update($attributes);

        $history = History::create([
            'user_id' => Auth::id(),
            'nama_tabel' => 'data siswa',
            'jenis' => 'ubah',
            'nama_data' => $attributes['nama'],
            'token_data' => $attributes['nis'],
        ]);
        
        return back()->withInput();
    }

    public function destroy(Request $request) {
        try {
            $siswa = Siswa::findOrFail($request->id);

            Siswa::destroy($siswa->id);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data siswa',
                'jenis' => 'hapus',
                'nama_data' => $siswa->nama,
                'token_data' => $siswa->nis,
            ]);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
