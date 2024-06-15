<?php
namespace App\DTOs;

class ParkingSpotDTO
{
    public int $parking_lot_id;
    public string $vehicle_type;

    public function __construct(array $data)
    {
        $this->parking_lot_id = $data['parking_lot_id'];
        $this->vehicle_type = $data['vehicle_type'];
    }

    // public function toArray(): array
    // {
    //     return [
    //         'parking_lot_id' => $this->parking_lot_id,
    //         'vehicle_type' => $this->vehicle_type,
    //     ];
    // }
}