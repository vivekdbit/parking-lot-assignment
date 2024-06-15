<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parkingSpots()
    {
        return $this->belongsToMany(ParkingSpot::class,'reservations_spots');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
