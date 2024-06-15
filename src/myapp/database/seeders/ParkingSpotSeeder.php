<?php

namespace Database\Seeders;

use App\Enums\SpotStatus;
use App\Enums\SpotType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParkingSpot;

class ParkingSpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30 entries with spot_type = 'REGULAR'
        for ($i = 1; $i <= 30; $i++) {
            ParkingSpot::create([
                'name' => 'REG-' . $i,
                'floor' => 1,
                'booked_at' => null,
                'parking_lot_id' => 1,
            ]);
        }
    }
}
