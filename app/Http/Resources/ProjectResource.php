<?php

namespace App\Http\Resources;

use App\Models\TaskStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'status' => []
        ];

        $statuses = $this->statuses;
        $i = 0;
        foreach ($statuses as $status) {
            $data['status'][$i]['detail'] = StatusResource::make($status);
            $data['status'][$i]['val'] = $this->tasks()->where('status_id', $status->id)->count();
            $i++;
        }

        return $data;
    }
}
