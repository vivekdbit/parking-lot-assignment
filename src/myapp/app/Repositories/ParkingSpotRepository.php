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

    // Book Single Spot
    public function bookSpot($data, $limit=1)
    {
        // Start a transaction
        DB::beginTransaction();

        try {
            // Find the parking spot and lock it for update
            // SKIP LOCKED for checking next record
            $query = "
                SELECT id 
                FROM parking_spots 
                WHERE parking_lot_id = :parking_lot_id 
                AND booked_at IS NULL 
                FOR UPDATE SKIP LOCKED
                LIMIT :limit
            ";

            $spot = DB::select($query, ['limit' => $limit, 'parking_lot_id' => $data->parking_lot_id])[0] ?? null;

            if (!$spot) {
                throw new Exception('Parking spot not available');
            }
            
            // Book the spot
            ParkingSpot::where('id', $spot->id)->update(['booked_at' => Carbon::now()]);
            DB::commit();

            //return ParkingSpot::where('id', $spot->id)->first();
            return $spot->id;
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            throw new Exception('Failed to book parking spot: ' . $e->getMessage());
        }
    }

    // Book Multiple Spot
    public function bookSpots($data, $limit=1)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            $query = "
                SELECT id
                FROM parking_spots 
                WHERE parking_lot_id = :parking_lot_id
                AND booked_at IS NULL
                ORDER BY id ASC
                LIMIT :limit
                FOR UPDATE SKIP LOCKED
            ";

            $spots = DB::select($query, ['limit'=> $limit,'parking_lot_id' => $data->parking_lot_id]) ?? null;

            if (!isset($spots) || count($spots)< $limit ) {
                // No consecutive spots found
                DB::rollback(); // Rollback transaction
                throw new Exception('Parking spot not available');
            }

            // Extract IDs from the result objects
            $spotIds = array_map(function ($spot) {
                return $spot->id;
            }, $spots);

            // Update spots with current timestamp as booked_at
            ParkingSpot::whereIn('id', $spotIds)->update(['booked_at' => Carbon::now()]);

            // Commit the transaction
            DB::commit();

            // Return the IDs of the booked spots
            return ParkingSpot::whereIn('id', $spotIds)->get();
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            throw new \Exception('Failed to book parking spot: ' . $e->getMessage());
        }
    }

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
