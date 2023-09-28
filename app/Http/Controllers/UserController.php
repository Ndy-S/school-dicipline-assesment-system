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

    public function create(Request $request) {

        dd($request);
    }


    public function destroy(Request $request) {
        $user = User::findOrFail($request->id);

        // $image_path = public_path("img/library/{$artLibrary->image_path}");
        // if (!str_contains($image_path ,'default.webp')) {
        //     unlink($image_path);
        // }

        User::destroy($user->id);
        return back();
    }
}
