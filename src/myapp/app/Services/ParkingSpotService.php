<?php

namespace App\Services;

use App\DTOs\ParkingSpotDTO;
use App\ParkingStrategies\ParkingStrategyContext;
use App\Repositories\ParkingSpotRepository;
use App\Repositories\ReservationRepository;
use Exception;

class ParkingSpotService
{
    protected $parkingSpotRepository;
    protected $reservationRepository;

    public function __construct(ParkingSpotRepository $parkingSpotRepository, ReservationRepository $reservationRepository)
    {
        $this->parkingSpotRepository = $parkingSpotRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function parkVehicle(ParkingSpotDTO $data)
    {
        try {
            $strategy = new ParkingStrategyContext($data->vehicle_type,$this->parkingSpotRepository, $this->reservationRepository);
            return $strategy->park($data);
        } catch (Exception $e) {
            throw new Exception('Failed to book parking spot: ' . $e->getMessage());
        }
    }

    public function unparkVehicle($reservation_id)
    {
        return $this->reservationRepository->unparkVehicle($reservation_id);
    }

    public function getParkingLotAvailability($id)
    {
        return $this->parkingSpotRepository->getAvailableSpots($id);
    }
}