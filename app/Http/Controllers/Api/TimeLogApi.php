<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TimeLogApi extends Controller
{
    /*
     * $id - project id
     */
    public function create(Request $request, string $id): JsonResponse
    {
        // move to some request validator
        $lastUnfinishedLog = TimeLog::where('user_id', $request->user()->id)
            ->where('project_id', $id)
            ->where('finished_at', null)
            ->where('started_at', '!=', null)
            ->first();

        // if user has sent a request to start logging time without finishing the previous one - skip it
        if ($lastUnfinishedLog) return response()->json(['lastUnfinishedLogId' => $lastUnfinishedLog->id]);

        $lastUnfinishedLog = TimeLog::create([
            'user_id' => $request->user()->id,
            'project_id' => $id,
            'started_at' => Carbon::now('CET')
        ]);

        return response()->json(['lastUnfinishedLogId' => $lastUnfinishedLog->id]);
    }

    /*
     * $id - time log id
     */
    public function stop(Request $request, string $id): JsonResponse
    {
        // validator should be used here to validate access to this time log
        TimeLog::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update([
                'finished_at' => Carbon::now('CET')
            ]);

        return response()->json(['timeLogId' => $id]);
    }

    /*
     * $id - project id
     */
    public function setManualLog(Request $request, string $id): JsonResponse|Response
    {
        $startDateObject = json_decode($request->post('startTime'));
        $startDate = Carbon::createFromTime(
            $startDateObject->hours,
            $startDateObject->minutes,
            $startDateObject->seconds,
            'CET'
        );

        $finishDateObject = json_decode($request->post('finishTime'));
        $finishDate = Carbon::createFromTime(
            $finishDateObject->hours,
            $finishDateObject->minutes,
            $finishDateObject->seconds,
            'CET'
        );

        $crossingTracking = TimeLog::where('user_id', $request->user()->id)
            ->where('project_id', $id)
            ->where(function($query) use ($startDate, $finishDate) {
                $query->where('started_at', '<=', $startDate)
                    ->where('finished_at', '>=', $startDate);
            })
            ->orWhere(function($query) use ($startDate, $finishDate) {
                $query->where('started_at', '<=', $finishDate)
                    ->where('finished_at', '>=', $finishDate);
            })
            ->orWhere(function($query) use ($startDate, $finishDate) {
                $query->where('started_at', '>=', $startDate)
                    ->where('started_at', '<=', $finishDate);
            })
            ->orWhere(function($query) use ($startDate, $finishDate) {
                $query->where('finished_at', '>=', $startDate)
                    ->where('finished_at', '<=', $finishDate);
            })
            ->first();

        if ($crossingTracking) return response('Crossing time logs, select other time', 400);

        $timeLog = TimeLog::create([
            'user_id' => $request->user()->id,
            'project_id' => $id,
            'started_at' => $startDate,
            'finished_at' => $finishDate,
        ]);

        return response()->json(['timeLogId' => $timeLog->id]);
    }

    /*
     * $id - time log id
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([]);
    }

    /*
     * $id - time log id
     */
    public function delete(Request $request, string $id): JsonResponse
    {
        return response()->json([]);
    }
}
