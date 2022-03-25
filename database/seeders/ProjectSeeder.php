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
            'Toko Daring Vendor',
            'Toko Daring Admin',
            'FJB Mandalika',
            'Lentera Monitoring'
        ];

        foreach($projects as $project) {
            Project::create([
                'name' => $project
            ]);
        }
    }
}
