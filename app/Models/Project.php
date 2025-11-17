<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'booking_id',
        'service_id',
        'service_ids',
        'lot_area',
        'cost',
        'total_cost',
        'description',
        'project_status',
        'discount',
        'start_date',
        'end_date',
    ];

    /**
     * Get the service associated with the project.
     */
    protected $casts = [
        'service_ids' => 'array',
    ];
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function progress()
    {
        return $this->hasMany(Progress::class); // Adjust based on your actual relationship
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    /**
     * Get the booking associated with the project.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
