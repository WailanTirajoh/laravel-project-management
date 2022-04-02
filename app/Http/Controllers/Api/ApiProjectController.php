<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('tasks')->get();

        return response()->json([
            'projects' => ProjectResource::collection($projects)
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            DB::beginTransaction();

            $project = Project::create([
                'name' => $request->name,
                'created_by' => Auth::user()->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Project created successfully!',
                'project' => ProjectResource::make($project->load('tasks'))
            ]);
        } catch (HttpException $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
