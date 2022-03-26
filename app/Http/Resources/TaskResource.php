<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'sort' => $this->sort,
            'start_date' => (new Carbon($this->start_date))->format('Y-m-d H:i:s'),
            'due_date' => $this->due_date,
            'finish_date' => $this->finish_date,
            'created_at_hour' => $this->created_at->format('H:i:s'),
            'created_at_date' => $this->created_at->format('d M Y'),
        ];
    }
}
