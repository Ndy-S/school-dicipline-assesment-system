<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Inertia\Inertia;
use App\Models\History;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard() {
        return Inertia::render('Dashboard', [
            'historyQuery' => History::query()->with('user')->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
