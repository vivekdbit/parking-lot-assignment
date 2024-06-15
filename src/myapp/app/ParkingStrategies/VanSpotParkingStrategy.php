<?php
namespace App\ParkingStrategies;

use App\DTOs\ParkingSpotDTO;
use App\Repositories\ParkingSpotRepository;
use App\Repositories\ReservationRepository;

class VanSpotParkingStrategy implements ParkingStrategy
{
    protected $parkingSpotRepository;
    protected $reservationRepository;
    protected $van_capacity = 3;

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
        return $this->parkVan($data);
    }

    private function parkVan(ParkingSpotDTO $data){
        
        $spots = $this->parkingSpotRepository->bookSpots($data, $this->van_capacity);

        // Create reservations for van
        return $this->reservationRepository->createReservationsForVan($spots->pluck('id')->toArray());
    }
}