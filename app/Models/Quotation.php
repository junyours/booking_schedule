<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'region',
        'lot_area',
        'service_id',
        'amount',
        'working_days',
    ];

    // Ensure correct casting of fields
    protected $casts = [
        'amount' => 'float',
        'working_days' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDesignUrlAttribute()
    {
        return $this->design ? asset('storage/' . $this->design) : 'path/to/default-image.png';
    }
}
