<?php

namespace Database\Seeders;

use App\Models\ParkingLot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingLotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParkingLot::create([
            'name' => 'Main Street 101',
            'address' => '123 Main St, Vancouver',
            'zipcode' => 'V2F F3K',
        ]);
    }
}
