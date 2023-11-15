<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProjectsController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Projects/Projects', [
            'projects' => Project::paginate(10)
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Projects/ProjectCreate', []);
    }

    public function store(Request $request): Response|HttpResponse
    {
        Project::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return to_route('admin.showProjects');
    }
}
