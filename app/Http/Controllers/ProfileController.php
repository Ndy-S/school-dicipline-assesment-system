<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\History;
use App\Models\MataPelajaran;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\SOP;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index() {
        $user = User::findOrFail(Auth::id());

        return Inertia::render('Profile', [
            'userProfile' => $user,
            'historyQuery' => History::query()
                ->where('user_id', $user->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get(),
            'createdBy' => History::query()
                ->where('jenis', 'tambah')
                ->where('nama_tabel', 'data user')
                ->where('nama_data', $user->nama)
                ->with('user')
                ->first(),
            'can' => [
                'viewUser' => Auth::user()->can('viewAny', User::class),
                'viewSiswa' => Auth::user()->can('viewAny', Siswa:: class),
                'viewGuru' => Auth::user()->can('viewAny', Guru:: class),
                'viewMataPelajaran' => Auth::user()->can('viewAny', MataPelajaran:: class),
                'viewSOP' => Auth::user()->can('viewAny', SOP:: class),
                'viewPelanggaran' => Auth::user()->can('viewAny', Pelanggaran::class),
            ]
        ]);
    }

    public function update(Request $request) {
        try {
            $attributes = $request->validate([
                'id' => 'required',
                'token' => 'required',
                'nama' => 'required',
            ]);

            $nullableFields = [
                'password',
                'image_path',
            ];

            foreach($nullableFields as $field) {
                if (!is_null($request->input($field))) {
                    $attributes = array_merge($attributes, [$field => $request->input($field)]);
                }
            }
            

            if ($request->file('image_path')) {
                $request->validate([
                    'image_path' => 'image|max:2048',
                ]);

                $image = $request->file('image_path');
                $extension = strtolower($image->getClientOriginalExtension());
                $image_name = md5(uniqid($image->getClientOriginalName(), true) . time()) . '.' . $extension;
                $attributes['image_path'] = $image_name;
                $image->move('./img/', $image_name);
                $attributes;
            } else if ($request->image_path) {
                array_merge($attributes, ['image_path' => $request->input('image_path')]);
            }

            $user = User::findOrFail($attributes['id']);

            $image_path = public_path("img/{$user->image_path}");

            if (!str_contains($image_path ,'default.png') && $attributes["image_path"] != $user->image_path) {
                unlink($image_path);
            }

            $user->update($attributes);

            $history = History::create([
                'user_id' => Auth::id(),
                'nama_tabel' => 'profil',
                'jenis' => 'ubah',
                'nama_data' => $attributes['nama'],
                'token_data' => $attributes['token'],
            ]);

            return back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
