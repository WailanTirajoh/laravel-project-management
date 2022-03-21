<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = TaskResource::collection(Auth::user()->pic_tasks()->get());
        $task_statuses = TaskStatus::get();

        return Inertia::render('Tasks.Index', [
            'task_statuses' => $task_statuses
        ]);
    }

    public function create()
    {
        //
    }

    public function store(StoreTaskRequest $request)
    {
        //
    }

    public function show(Task $task)
    {
        //
    }

    public function edit(Task $task)
    {
        //
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    public function destroy(Task $task)
    {
        //
    }
}
