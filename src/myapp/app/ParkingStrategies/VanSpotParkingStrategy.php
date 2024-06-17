<?php
namespace App\ParkingStrategies;

use App\DTOs\ParkingSpotDTO;
use App\Repositories\ParkingSpotRepository;
use App\Repositories\ReservationRepository;
use Exception;
use Illuminate\Support\Facades\DB;

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
        
        // Start a transaction
        DB::beginTransaction();

        try {
            // Book Spot
            $spot = $this->parkingSpotRepository->bookSpots($data, $this->van_capacity);

            if (!$spot) {
                throw new Exception('Parking spot not available');
            }

            // Update spots as booked
            $spot_ids = array_column($spot, 'id');
            $this->parkingSpotRepository->updateSpotsAsBooked($spot_ids);

            // Create reservations for Van
            $reservation =  $this->reservationRepository->createReservationsForVan($spot_ids);

            DB::commit();
            return $reservation;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Failed to book parking spot: ' . $e->getMessage());
        }
    }
}