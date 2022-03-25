<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiTaskController extends Controller
{
    public function index(Request $request)
    {
        return TaskResource::collection(Task::when($request->status, function ($query) use ($request) {
            $query->whereHas('status', function ($query) use ($request) {
                if ($request->status !== 'All') {
                    $query->where('name', 'like', "%{$request->status}%");
                }
            });
        })->when($request->project_id, function ($query) use ($request) {
            if ($request->project_id !== 'All') {
                $query->where('project_id', $request->project_id);
            }
        })->get());
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            DB::beginTransaction();

            $task = new Task();
            $task->name = $request->name;
            $task->description = $request->description;
            $task->status_id = $request->status_id;
            $task->project_id = $request->project_id;
            $task->notes = $request->notes;
            $task->pic_id = Auth::user()->id;
            $task->start_date = now();
            $task->finish_date = now();
            $task->save();

            DB::commit();

            return response()->json([
                'message' => 'Success store task',
                'task' => TaskResource::make($task)
            ]);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            DB::beginTransaction();

            $task->name = $request->name;
            $task->description = $request->description;
            $task->status_id = $request->status_id;
            $task->project_id = $request->project_id;
            $task->notes = $request->notes;
            $task->save();

            DB::commit();

            return response()->json([
                'message' => 'Success update task',
                'task' => TaskResource::make($task)
            ]);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();

            $task->delete();

            DB::commit();

            return response()->json([
                'message' => 'Task deleted successfully'
            ]);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
