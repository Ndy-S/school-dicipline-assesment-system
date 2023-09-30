<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'token' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('token', $request->token)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'error' => 'Data yang anda berikan salah.'
        ]);
    }

    public function logout() {
        Auth::logout();

        return redirect()->route('login');
    }
}
