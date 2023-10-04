<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\History;
use App\Models\MataPelajaran;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\SOP;

class GuruController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:nama,nip,gender,piket,alamat,handphone']
        ]);

        $guruQuery = Guru::query();

        if (request('search')) {
            $guruQuery
                ->where('nama', 'LIKE', '%'.request('search').'%')
                ->orWhere('nip', 'LIKE', '%'.request('search').'%')
                ->orWhere('gender', 'LIKE', '%'.request('search').'%')
                ->orWhere('piket', 'LIKE', '%'.request('search').'%')
                ->orWhere('alamat', 'LIKE', '%'.request('search').'%')
                ->orWhere('handphone', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $guruQuery->orderBy(request('field'), request('direction'));
        }
        
        return Inertia::render('Guru', [
            'guruQuery' => $guruQuery->get(),
            'guruPaginate' => $guruQuery->with('user')->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
            'historyQuery' => History::query()->where('nama_tabel', 'data guru')->with('user')->orderBy('created_at', 'desc')->get(),
            'userQuery' => User::query()->where('peran', 'Guru')->orderBy('nama', 'asc')->orderBy('token', 'asc')->get()->map(function ($user) {
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
                'createGuru' => Auth::user()->can('create', Guru::class),
            ]
        ]);
    }

    public function dataProcess($request) {
        try {
            $attributes = $request->validate([
                'nama' => 'required',
                'nip' => 'required',
                'gender' => 'required',
            ]);

            $nullableFields = [
                'piket',
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

        $guru = Guru::create($attributes);

        $history = History::create([
            'user_id' => Auth::id(),
            'nama_tabel' => 'data guru',
            'jenis' => 'tambah',
            'nama_data' => $attributes['nama'],
            'token_data' => $attributes['nip'],
        ]);

        return back()->withInput();
    }
    
    public function update(Request $request) {
        $attributes = $this->dataProcess($request);

        $guru = Guru::findOrFail($attributes['id']);
        $guru->update($attributes);
        
        $history = History::create([
            'user_id' => Auth::id(),
            'nama_tabel' => 'data guru',
            'jenis' => 'ubah',
            'nama_data' => $attributes['nama'],
            'token_data' => $attributes['nip'],
        ]);

        return back()->withInput();
    }

    public function destroy(Request $request) {
        try {
            $guru = Guru::findOrFail($request->id);

            Guru::destroy($guru->id);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data guru',
                'jenis' => 'hapus',
                'nama_data' => $guru->nama,
                'token_data' => $guru->nip,
            ]);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
