<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:nama,token,peran']
        ]);

        $userQuery = User::query();

        if (request('search')) {
            $userQuery
                ->where('nama', 'LIKE', '%'.request('search').'%')
                ->orWhere('token', 'LIKE', '%'.request('search').'%')
                ->orWhere('peran', 'LIKE', '%'.request('search').'%');
        }

        if (request()->has(['field', 'direction'])) {
            $userQuery->orderBy(request('field'), request('direction'));
        }

        return Inertia::render('User', [
            'userQuery' => $userQuery->get(),
            'userPaginate' => $userQuery->orderBy('created_at', 'desc')->paginate('10')->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction'])
        ]);    
    }


    public function dataProcess($request) {
        try {
            $attributes = $request->validate([
                'token' => 'required',
                'password' => 'required',
                'peran' => 'required',
                'nama' => 'required',
            ]);

            $nullableFields = [
                'id',
            ];

            foreach($nullableFields as $field) {
                if (!is_null($request->input($field))) {
                    $attributes = array_merge($attributes, [$field => $request->input($field)]);
                } else if (is_null($request->input($field)) && $request->has('id')) {
                    $attributes = array_merge($attributes, [$field => 'N/A']);
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
                return $attributes;
            } else if ($request->image_path) {
                return array_merge($attributes, ['image_path' => $request->input('image_path')]);
            }
            return $attributes;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request) {
        try {
            $attributes = $this->dataProcess($request);

            $artLibrary = User::create($attributes);
            return back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function destroy(Request $request) {
        $user = User::findOrFail($request->id);

        $image_path = public_path("img/{$user->image_path}");
        if (!str_contains($image_path ,'default.png')) {
            unlink($image_path);
        }

        User::destroy($user->id);
        return back();
    }
}
