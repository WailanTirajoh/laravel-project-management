<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pic' => $this->pic,
            'status_id' => $this->status_id,
            'project_id' => $this->project_id,
            'status' => $this->status,
            'project' => $this->project,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
        ];
    }
}
