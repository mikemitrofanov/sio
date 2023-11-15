<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create default users - admin and regular user
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin',
            'role' => 'admin'
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user'
        ]);

        $project = DB::table('projects')->first();
        if ($project) {
            DB::table('user_project')->insert([
                'user_id' => $user->id,
                'project_id' => $project->id,
            ]);
        }
    }
}
