<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks.Index', [
            'task_statuses' => TaskStatus::select('id', 'name', 'color')->get(),
            'projects' => Auth::user()->projects()->select('projects.id', 'name')->with(['tasks' => function ($task) {
                $task->select((new Task())->getTable() . '.id', 'name', 'description', 'project_id');
            }])
                ->withCount('tasks')
                ->orderByDesc('tasks_count')
                ->get()
        ]);
    }
}
