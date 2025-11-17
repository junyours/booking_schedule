<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renovation extends Model
{
    use HasFactory;

    protected $table = 'renovation';

    protected $fillable = [
        'name',
        'type',
        'design',
        'description',
        'complexity',
        'category_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Service::class, 'category_id');
    }
    public function quotations()
    {
        return $this->morphMany(Quotation::class, 'designable');
    }
}
