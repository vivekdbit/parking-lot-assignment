<?php

use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Reservation::class)->index();
            $table->foreignIdFor(ParkingSpot::class)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations_spots');
    }
};
