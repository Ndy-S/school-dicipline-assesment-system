<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Inertia\Inertia;
use Illuminate\Http\Request;


class SiswaController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:nama,nis,kelas,gender,agama,alamat,no_hp']
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
                ->orWhere('no_hp', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $siswaQuery->orderBy(request('field'), request('direction'));
        }

        return Inertia::render('Siswa', [
            'siswaQuery' => $siswaQuery->get(),
            'siswaPaginate' => $siswaQuery->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
        ]);
    }
}
