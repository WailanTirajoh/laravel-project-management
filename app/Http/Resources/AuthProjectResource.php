<?php

namespace App\Http\Resources;

use App\Models\TaskStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->project->id,
            'name' => $this->project->name,
            'status' => []
        ];

        $statuses = TaskStatus::get();
        $i = 0;
        foreach ($statuses as $status) {
            $data['status'][$i]['detail'] = StatusResource::make($status);
            $data['status'][$i]['val'] = $this->project->tasks()->where('status_id', $status->id)->count();
            $i++;
        }

        return $data;
    }
}
