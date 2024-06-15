<?php
namespace App\ParkingStrategies;

use App\DTOs\ParkingSpotDTO;
use App\Enums\SpotType;
use App\Repositories\ParkingSpotRepository;
use App\Repositories\ReservationRepository;

class MotorcycleSpotParkingStrategy implements ParkingStrategy
{
    protected $parkingSpotRepository;
    protected $reservationRepository;

    public function __construct(ParkingSpotRepository $parkingSpotRepository, ReservationRepository $reservationRepository)
    {
        $this->parkingSpotRepository = $parkingSpotRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function park(ParkingSpotDTO $data)
    {
        $spot = $this->parkingSpotRepository->bookSpot($data);
        
        // Create reservations for motorcycle
        return $this->reservationRepository->createReservationForMotorCycle($spot);
    }
}