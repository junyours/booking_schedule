<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    // Specify the table name (optional if it matches the plural form)
    protected $table = 'rates';

    // The attributes that are mass assignable
    protected $fillable = [
        'service_type',
        'region',
        'complexity',
        'rate',
    ];

    // Optionally specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Optionally disable timestamps if not needed
    public $timestamps = true;
}
