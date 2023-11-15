<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // default project that assigned to the default user
        DB::table('projects')->insert([
            'title' => 'Test project',
            'description' => 'Test description',
        ]);
    }
}
