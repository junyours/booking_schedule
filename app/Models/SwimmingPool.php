<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwimmingPool extends Model
{
    use HasFactory;

    protected $table = 'swimmingpool';

    protected $fillable = [
        'name',
        'design',
        'description',
        'complexity',
        'status',
        'category_id',
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
