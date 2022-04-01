<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'project_task_statuses';

    protected $fillable = [
        'name',
        'color',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
