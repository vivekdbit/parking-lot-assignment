<?php

namespace App\Repositories;

use App\Enums\SpotType;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ReservationRepository
{
    protected $model;

    public function __construct(Reservation $reservation)
    {
        $this->model = $reservation;
    }

    public function createReservationForCar($spot_id)
    {
        // Create a single reservation entry for a car
        $reservation = Reservation::create([
            'spot_type' => SpotType::CAR->value,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addHour(),
        ]);

        // Attach the parking spot to the reservation
        $reservation->parkingSpots()->attach($spot_id);

        return $reservation;
    }

    public function createReservationForMotorcycle($spot_id)
    {
        // Create a single reservation entry for a car
        return Reservation::create([
            'spot_type' => SpotType::MOTORCYCLE->value,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addHour(),
        ])->ParkingSpots()->attach($spot_id);
    }

    public function createReservationsForVan($spot_ids)
    {
        // Create the reservation for the van
        $reservation = Reservation::create([
            'spot_type' => SpotType::VAN->value,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addHour(),
        ]);

        // Attach the multiple parking spots to the reservation
        $reservation->parkingSpots()->attach($spot_ids);

        return $reservation;
    }

    public function unparkVehicle($reservationId)
    {
        // Begin transaction
        DB::beginTransaction();

        try {
            // Retrieve the reservation
            $reservation = Reservation::findOrFail($reservationId);

            // Get the associated parking spots
            $parkingSpots = $reservation->parkingSpots;

            // Update the booked_at field to null for the parking spots
            foreach ($parkingSpots as $spot) {
                $spot->booked_at = null;
                $spot->save();
            }

            // Commit transaction
            DB::commit();

            return $parkingSpots->pluck('id'); // Return the IDs of the unparked spots

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();
            throw new \Exception('Failed to unpark vehicle: ' . $e->getMessage());
        }
    }
}