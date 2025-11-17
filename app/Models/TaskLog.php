<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow the convention
    protected $table = 'task_logs';

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'type_id',
        'type',
        'action',
        'action_date',
    ];

    // Optionally, you can define date casting
    protected $casts = [
        'action_date' => 'datetime',
    ];
}
