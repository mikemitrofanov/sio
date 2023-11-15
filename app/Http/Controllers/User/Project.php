<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Project extends Controller
{
    public function show(Request $request, string $id): Response
    {
        // if started tracking and left the page without finishing - get the last not finished log
        $lastUnfinishedLog = TimeLog::where('user_id', $request->user()->id)
            ->where('project_id', $id)
            ->where('started_at', '!=', null)
            ->where('finished_at', null)
            ->first();

        return Inertia::render('TimeLog/TimeLog', [
            'lastUnfinishedLog' => $lastUnfinishedLog,
            'projectId' => $id,
        ]);
    }

}
