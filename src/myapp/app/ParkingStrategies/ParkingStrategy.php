<?php
namespace App\ParkingStrategies;

use App\DTOs\ParkingSpotDTO;

interface ParkingStrategy
{
    public function park(ParkingSpotDTO $data);
}