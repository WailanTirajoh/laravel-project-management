<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiTaskSortController extends Controller
{
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->sortable as $sortable) {
                $task = Task::find($sortable['id']);
                $task->sort = $sortable['index'];
                $task->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Saved'
            ]);
        } catch (HttpException $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
