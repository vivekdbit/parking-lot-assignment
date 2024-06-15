<?php
namespace App\ParkingStrategies;

use App\DTOs\ParkingSpotDTO;
use App\Repositories\ParkingSpotRepository;
use App\Repositories\ReservationRepository;

class CarSpotParkingStrategy implements ParkingStrategy
{
    protected $parkingSpotRepository;
    protected $reservationRepository;

    public function __construct(
        ParkingSpotRepository $parkingSpotRepository, 
        ReservationRepository $reservationRepository
    )
    {
        $this->parkingSpotRepository = $parkingSpotRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function park(ParkingSpotDTO $data)
    {
        $spot = $this->parkingSpotRepository->bookSpot($data);

        // Create reservations for van
        $reservation =  $this->reservationRepository->createReservationForCar($spot);
        return $reservation;
    }
}