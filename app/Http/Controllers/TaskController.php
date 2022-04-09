<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TaskStatus;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks.Index', [
            'task_statuses' => TaskStatus::select('id', 'name', 'color')->get(),
            'projects' => Project::select('id', 'name')->with(['tasks' => function ($task) {
                $task->select('id', 'name', 'description', 'project_id');
            }])
                ->withCount('tasks')
                ->orderByDesc('tasks_count')
                ->get()
        ]);
    }
}
