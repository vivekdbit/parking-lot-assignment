<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reservation_id' => $this->id,
            'user_id' => $this->user_id,
            'vehicle_license' => $this->vehicle_license,
            'spot_type' => $this->spot_type,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'paid_at' => $this->paid_at,
            'spots' => $this->parkingSpots->map(function ($spot) {
                return [
                    'id' => $spot->id,
                    'booked_at' => $spot->booked_at,
                    'floor' => $spot->floor,
                ];
            }),
        ];
    }
}
