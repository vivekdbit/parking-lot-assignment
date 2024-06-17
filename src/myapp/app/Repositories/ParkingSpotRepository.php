<?php

namespace App\Repositories;

use App\Enums\SpotStatus;
use App\Models\ParkingSpot;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ParkingSpotRepository
{
    protected $model;

    public function __construct(ParkingSpot $parkingSpot)
    {
        $this->model = $parkingSpot;
    }

    public function getAvailableSpots($parking_lot_id)
    {
        return $this->model::where('parking_lot_id', $parking_lot_id)
                    ->whereNull('booked_at')
                    ->get();
    }

    // Select Spot for Booking
    public function bookSpots($data, $limit=1)
    {
        // Find the parking spot and lock it for update
        // SKIP LOCKED for checking next record
        $query = "
            SELECT id 
            FROM parking_spots 
            WHERE parking_lot_id = :parking_lot_id 
            AND booked_at IS NULL
            ORDER BY id ASC
            FOR UPDATE SKIP LOCKED
            LIMIT :limit
        ";

        return DB::select($query, ['limit' => $limit, 'parking_lot_id' => $data->parking_lot_id]) ?? null;
    }

    public function updateSpotsAsBooked($spot_ids)
    {
        return ParkingSpot::whereIn('id', $spot_ids)->update(['booked_at' => Carbon::now()]);
    }

    // Book Multiple Spot
    // public function bookSpots($data, $limit=1)
    // {
    //     try {
    //         // Begin transaction
    //         DB::beginTransaction();

    //         $query = "
    //             SELECT id
    //             FROM parking_spots 
    //             WHERE parking_lot_id = :parking_lot_id
    //             AND booked_at IS NULL
    //             ORDER BY id ASC
    //             LIMIT :limit
    //             FOR UPDATE SKIP LOCKED
    //         ";

    //         $spots = DB::select($query, ['limit'=> $limit,'parking_lot_id' => $data->parking_lot_id]) ?? null;

    //         if (!isset($spots) || count($spots)< $limit ) {
    //             // No consecutive spots found
    //             DB::rollback(); // Rollback transaction
    //             throw new Exception('Parking spot not available');
    //         }

    //         // Extract IDs from the result objects
    //         $spotIds = array_map(function ($spot) {
    //             return $spot->id;
    //         }, $spots);

    //         // Update spots with current timestamp as booked_at
    //         ParkingSpot::whereIn('id', $spotIds)->update(['booked_at' => Carbon::now()]);

    //         // Commit the transaction
    //         DB::commit();

    //         // Return the IDs of the booked spots
    //         return ParkingSpot::whereIn('id', $spotIds)->get();
            
    //     } catch (\Exception $e) {
    //         // Rollback the transaction on error
    //         DB::rollback();
    //         throw new \Exception('Failed to book parking spot: ' . $e->getMessage());
    //     }
    // }

    public function unpark($spot_id)
    {
        $spot = $this->model::findOrFail($spot_id);

        if ($spot->booked_at === null) {
            throw new \Exception('Parking spot is already vacant.');
        }

        $spot->booked_at = null;
        $spot->save();

        return $spot;
    }
}
