<?php

namespace App\Http\Requests\Api;

use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
        ];
    }
}
