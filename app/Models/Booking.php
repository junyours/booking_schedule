<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $table = 'bookings';
    
    protected $fillable = [
        
        'user_id',
        'name',
        'contact',
        'email',
        'address',
        'city',
        'province',
        'site_visit_date',
        'booking_status',

    ];

    /**
     * Get the user associated with the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

}
