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
                'name' => 'CREATED',
                'color' => '#ffffff'
            ],
            [
                'name' => 'ON_PROGRESS',
                'color' => '#ffffff'
            ],
            [
                'name' => 'DONE',
                'color' => '#ffffff'
            ],
            [
                'name' => 'ON_HOLD',
                'color' => '#ffffff'
            ],
            [
                'name' => 'CANCEL',
                'color' => '#ffffff'
            ],
        ];

        foreach ($statuses as $status) {
            TaskStatus::create($status);
        }
    }
}
