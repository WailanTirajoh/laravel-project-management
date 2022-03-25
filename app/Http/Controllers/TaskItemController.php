<?php

namespace App\Http\Controllers;

use App\Models\ProjectTaskItem;
use App\Http\Requests\StoreProjectTaskItemRequest;
use App\Http\Requests\UpdateProjectTaskItemRequest;

class ProjectTaskItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectTaskItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectTaskItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectTaskItem  $projectTaskItem
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectTaskItem $projectTaskItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectTaskItem  $projectTaskItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectTaskItem $projectTaskItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectTaskItemRequest  $request
     * @param  \App\Models\ProjectTaskItem  $projectTaskItem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectTaskItemRequest $request, ProjectTaskItem $projectTaskItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectTaskItem  $projectTaskItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectTaskItem $projectTaskItem)
    {
        //
    }
}
