<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Users/Users', [
            'users' => User::get()
        ]);
    }
}
