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
                'name' => 'Not Yet Started',
                'color' => '#fbe4d5'
            ],
            [
                'name' => 'On Progress',
                'color' => '#fff200'
            ],
            [
                'name' => 'On Hold',
                'color' => '#fe4611'
            ],
            [
                'name' => 'Cancel',
                'color' => '#0c1427'
            ],
            [
                'name' => 'Done',
                'color' => '#00b050'
            ],
        ];

        foreach ($statuses as $status) {
            $status['created_by'] = 1;
            TaskStatus::create($status);
        }
    }
}
