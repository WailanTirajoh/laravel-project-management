<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'Created',
                'color' => '#000000'
            ],
            [
                'name' => 'On Progress',
                'color' => '#000000'
            ],
            [
                'name' => 'Done',
                'color' => '#000000'
            ],
            [
                'name' => 'On Hold',
                'color' => '#000000'
            ],
            [
                'name' => 'Cancel',
                'color' => '#000000'
            ],
        ];

        foreach ($statuses as $status) {
            $status['created_by'] = 1;
            $status['project_id'] = 1;
            TaskStatus::create($status);
        }
    }
}
