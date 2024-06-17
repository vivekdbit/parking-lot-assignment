<?php

namespace App\Http\Controllers;

use App\DTOs\ParkingSpotDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingSpotRequest;
use App\Http\Resources\ParkingLotAvailabilityResource;
use App\Http\Resources\ParkingSpotResource;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\Request;
use App\Services\ParkingSpotService;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingSpotController extends Controller
{
    protected $parkingSpotService;

    public function __construct(ParkingSpotService $parkingSpotService)
    {
        $this->parkingSpotService = $parkingSpotService;
    }

    public function park(int $id, ParkingSpotRequest $request): JsonResource
    {
        try {
            $validatedData = $request->validated();
            $validatedData['parking_lot_id'] = $id;

            // Create DTO
            $data = new ParkingSpotDTO($validatedData);
            $reservation = $this->parkingSpotService->parkVehicle($data);

            if ($reservation === null) {
                throw new Exception('Failed to create reservation');
            }
    
            return new ReservationResource($reservation);
        } catch (Exception $e) {
            return new JsonResource(['message' => $e->getMessage()]);
        }
    }

    public function unpark(int $reservation_id): JsonResponse
    {
        try {
            $spot = $this->parkingSpotService->unparkVehicle($reservation_id);

            return response()->json([
                'message' => 'Vehicle unparked successfully',
                'spot_ids' => $spot,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    
    public function getAvailability($id): JsonResource
    {
        try {
            $availableSpots = $this->parkingSpotService->getParkingLotAvailability($id);
            return new ParkingLotAvailabilityResource($availableSpots);
        } catch (\Exception $e) {
            Log::error('Error fetching parking lot availability: ' . $e->getMessage());
            return new JsonResource(['message' => $e->getMessage()]);
        }
    }
}
