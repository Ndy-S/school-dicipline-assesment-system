<?php

namespace App\Http\Controllers;

use App\Models\SOP;
use App\Models\History;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SOPController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:kategori,deskripsi,sanksi']
        ]);

        $SOPQuery = SOP::query();

        if (request('search')) {
            $SOPQuery
                ->where('kategori', 'LIKE', '%'.request('search').'%')
                ->orWhere('deskripsi', 'LIKE', '%'.request('search').'%')
                ->orWhere('sanksi', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $SOPQuery->orderBy(request('field'), request('direction'));
        }
        
        return Inertia::render('SOP', [
            'SOPQuery' => $SOPQuery->get(),
            'SOPPaginate' => $SOPQuery->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction']),
            'historyQuery' => History::query()->where('nama_tabel', 'data SOP & peraturan')->with('user')->orderBy('created_at', 'desc')->get(),
        ]);
    }


    public function dataProcess($request) {
        try {
            $attributes = $request->validate([
                'kategori' => 'required',
            ]);

            $nullableFields = [
                'id',
                'deskripsi',
                'sanksi'
            ];

            foreach($nullableFields as $field) {
                if (!is_null($request->input($field))) {
                    $attributes = array_merge($attributes, [$field => $request->input($field)]);
                } else if ($request->input('id') && is_null($request->input($field))) {
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

            $SOP = SOP::create($attributes);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data SOP & peraturan',
                'jenis' => 'tambah',
                'nama_data' => $attributes['kategori'],
                'token_data' => '-',
            ]);

            return back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {
        try {
            $attributes = $this->dataProcess($request);

            $SOP = SOP::findOrFail($attributes['id']);

            $SOP->update($attributes);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data SOP & peraturan',
                'jenis' => 'ubah',
                'nama_data' => $attributes['kategori'],
                'token_data' => '-',
            ]);

            return back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function destroy(Request $request) {
        try {
            $SOP = SOP::findOrFail($request->id);

            SOP::destroy($SOP->id);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'data SOP & peraturan',
                'jenis' => 'hapus',
                'nama_data' => $SOP->kategori,
                'token_data' => '-',
            ]);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
