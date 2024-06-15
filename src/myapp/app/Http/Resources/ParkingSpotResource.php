<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingSpotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource === null) {
            return [];
        }

        return [
            'id' => $this->id,
            'parking_lot_id' => $this->parking_lot_id,
            'name' => $this->name,
            'floor' => $this->floor,
            'status' => $this->status,
            'booked_at' => $this->booked_at,
        ];
    }
}
