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
                'color' => '#ffffff'
            ],
            [
                'name' => 'On Progress',
                'color' => '#ffffff'
            ],
            [
                'name' => 'Done',
                'color' => '#ffffff'
            ],
            [
                'name' => 'On Hold',
                'color' => '#ffffff'
            ],
            [
                'name' => 'Cancel',
                'color' => '#ffffff'
            ],
        ];

        foreach ($statuses as $status) {
            TaskStatus::create($status);
        }
    }
}
