<?php

namespace Database\Seeders;

use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyTrackings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get first regular user
        $user = User::where('role', 'user')->first();
        $project = $user->projects()->first();
        if (!$project) {
            $project = DB::table('projects')->first();
            DB::table('user_project')->insert([
                'user_id' => $user->id,
                'project_id' => $project->id,
            ]);
        }

        $firstDay = new Carbon();
        $firstDay->startOfMonth();

        $lastDay = new Carbon();
        $lastDay->endOfMonth();

        while($firstDay->lt($lastDay)) {
            // skip current day for presentation
            if (intval($firstDay->format('d')) === intval(Carbon::now()->format('d'))) continue;

            for ($i = 0; $i < 5; $i++) {
                $firstDay->addSeconds(rand(0, 3600));
                $startDate = $firstDay->toDateTimeString();

                $firstDay->addSeconds(rand(0, 3600));
                $endDate = $firstDay->toDateTimeString();

                TimeLog::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'started_at' => $startDate,
                    'finished_at' => $endDate,
                ]);
            }
            $firstDay->addDay();
        }
    }
}
