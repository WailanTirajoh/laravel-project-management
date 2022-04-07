<?php

namespace App\Http\Requests\Api;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => [
                'required',
                'in:' . implode(',', Project::all()->pluck('id')->toArray())
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'required',
            ],
            'status_id' => [
                'required',
                'in:' . implode(',', TaskStatus::all()->pluck('id')->toArray())
            ],
            'start_date' => [
                'required',
                'date'
            ],
            'due_date' => [
                'required',
                'date'
            ],
            'parent_id' => [
                'nullable',
                'in:' . implode(',', Task::where('project_id', $this->request->get('project_id'))->pluck('id')->toArray())
            ]
        ];
    }
}
