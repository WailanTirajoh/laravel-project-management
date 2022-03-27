<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusColorRequest;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiStatusColorController extends Controller
{
    public function update(UpdateStatusColorRequest $request, TaskStatus $status)
    {
        try {
            DB::beginTransaction();

            $status->update([
                'color' => $request->color
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Color updated'
            ]);
        } catch (HttpException $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
