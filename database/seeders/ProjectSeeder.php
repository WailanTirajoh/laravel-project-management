<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            'My First Project',
        ];

        foreach ($projects as $project) {
            $project = Project::create([
                'created_by' => 1,
                'name' => $project,
            ]);

            $project->users()->attach(1);
        }
    }
}
