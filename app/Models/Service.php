<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'services';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'category', 
        'name', 
        'design', 
        'description', 
        'complexity', 
        'status',
        'type', 

    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
