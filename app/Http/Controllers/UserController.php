<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $userQuery = User::query();

        return Inertia::render('User', [
            'userQuery' => $userQuery->get(),
            'userPaginate' => $userQuery->orderBy('created_at', 'desc')->paginate('10')->withQueryString()
        ]);    
    }
}
