<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TimeLog as LogModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TimeLog extends Controller
{
    public function show(Request $request, string $id): Response
    {
        // if started tracking and left the page without finishing - get the last not finished log
        $lastUnfinishedLog = LogModel::where('user_id', $request->user()->id)
            ->where('project_id', $id)
            ->where('started_at', '!=', null)
            ->where('finished_at', null)
            ->first();

        return Inertia::render('TimeLog/TimeLog', [
            'lastUnfinishedLog' => $lastUnfinishedLog,
            'projectId' => $id,
        ]);
    }

    public function showLogs(Request $request, string $id): Response
    {
        $logs = LogModel::where('user_id', $request->user()->id)
            ->where('project_id', $id)
            ->paginate(10);

        return Inertia::render('TimeLog/TimeLogHistory', [
            'logs' => $logs,
            'projectId' => $id
        ]);
    }

    public function showLog(Request $request, string $id, string $logId): Response|HttpResponse
    {
        $log = LogModel::where('id', $logId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$log) return redirect('/dashboard');

        return Inertia::render('TimeLog/TimeLogEdit', [
            'log' => $log,
            'projectId' => $id
        ]);
    }

    public function edit(Request $request, string $id, string $logId): Response|HttpResponse
    {
        // move this check to validators and provide injection via Repository?
        $log = LogModel::where('id', $logId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$log) return redirect('/dashboard');

        // add validation for crossing time ranges ???
        $startDate = Carbon::createFromTime(
            $request->startTime['hours'],
            $request->startTime['minutes'],
            $request->startTime['seconds'],
            'CET'
        );
        $finishDate = Carbon::createFromTime(
            $request->finishTime['hours'],
            $request->finishTime['minutes'],
            $request->finishTime['seconds'],
            'CET'
        );

        $log->started_at = $startDate;
        $log->finished_at = $finishDate;
        $log->save();

        return to_route('timelog.showLogs', ['id' => $log->project_id]);
    }

    public function delete(Request $request, string $id, string $logId): Response|HttpResponse
    {
        // move this check to validators and provide injection via Repository?
        $log = LogModel::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$log) return redirect('/dashboard');

        $log->delete();

        return to_route('timelog.showLogs', ['id' => $log->project_id]);
    }

    public function showStatistics(Request $request, string $id): Response
    {
        $logs = LogModel::where('user_id', $request->user()->id)
            ->where('project_id', $id)
            ->get();

        return Inertia::render('TimeLog/TimeLogsStatistics', [
            'logs' => $logs,
            'projectId' => $id
        ]);
    }
}
