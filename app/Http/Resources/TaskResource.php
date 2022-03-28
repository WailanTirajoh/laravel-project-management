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
            'start_date' => str_replace(' ', 'T', (new Carbon($this->start_date))->format('Y-m-d H:i')),
            'due_date' => str_replace(' ', 'T', (new Carbon($this->due_date))->format('Y-m-d H:i')),
            'finish_date' => $this->finish_date,
            'due_date_hour' => (new Carbon($this->due_date))->format('H:i:s'),
            'due_date_date' => (new Carbon($this->due_date))->format('d M Y'),
            'due_left' => ($this->due_date > now() ? 'Due in ' : 'Expired ') . (new Carbon($this->due_date))->diffForHumans(),
            'due_date_default' => $this->due_date,
        ];
    }
}
