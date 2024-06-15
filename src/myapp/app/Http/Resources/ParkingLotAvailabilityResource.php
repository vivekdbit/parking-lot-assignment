<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingLotAvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $availableSpots = $this->resource;

        return [
            'total_count' => $availableSpots->count(),
            'available_spots' => $availableSpots->map(function ($spot) {
                return [
                    'id' => $spot->id,
                    'parking_lot_id' => $spot->parking_lot_id,
                    'name' => $spot->name,
                    'floor' => $spot->floor,
                    'booked_at' => $spot->booked_at,
                ];
            })
        ];
    }
}
