<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UsersController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Users/Users', [
            'users' => User::paginate(10)
        ]);
    }

    public function edit(Request $request, string $id): Response
    {
        return Inertia::render('Users/UsersEdit', [
            'user' => User::find($id)->load('projects'),
            'projects' => Project::all()
        ]);
    }

    public function save(Request $request, string $id): Response|HttpResponse
    {
        $user = User::where('id', $id)->first();
        $attachedProjects = array_map(function($p) { return $p['id']; }, $user->projects()->get()->toArray());
        $diffDetached = array_diff($attachedProjects, $request->mappedProjects);
        $diffAttached = array_diff($request->mappedProjects, $attachedProjects);

        $user->projects()->detach($diffDetached);
        $user->projects()->attach($diffAttached);

        return to_route('admin.showUsers');
    }
}
