<?php
namespace App\ParkingStrategies;

use App\DTOs\ParkingSpotDTO;
use App\Repositories\ParkingSpotRepository;
use App\Repositories\ReservationRepository;

class ParkingStrategyContext
{
    private $strategy;

    public function __construct(string $vehicleType, ParkingSpotRepository $parkingSpotRepository, ReservationRepository $reservationRepository)
    {
        $this->strategy = match($vehicleType){
            'car' => new CarSpotParkingStrategy($parkingSpotRepository,$reservationRepository),
            'motorcycle' => new MotorcycleSpotParkingStrategy($parkingSpotRepository,$reservationRepository),
            'van' => new VanSpotParkingStrategy($parkingSpotRepository,$reservationRepository),
            default => throw new \InvalidArgumentException('Invalid vehicle type.')
        };
    }

    public function park(ParkingSpotDTO $data) 
    {
        return $this->strategy->park($data);
    }
}