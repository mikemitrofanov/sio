<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CSVExportApi extends Controller
{
    public function export(Response $response): StreamedResponse
    {
        $fileName = 'logs.csv';
        $logs = TimeLog::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Id', 'Email', 'Project Title', 'Start log', 'End log');

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $row['Id'] = $log->id;
                fputcsv($file, array($row['Id']));
//                $row['Title']  = $task->title;
//                $row['Assign']    = $task->assign->name;
//                $row['Description']    = $task->description;
//                $row['Start Date']  = $task->start_at;
//                $row['Due Date']  = $task->end_at;

//                fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
