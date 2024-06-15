<?php

use App\Enums\SpotStatus;
use App\Enums\SpotType;
use App\Models\ParkingLot;
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
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ParkingLot::class)->index();
            $table->string('name', 20);
            $table->smallInteger('floor')->default(0);
            $table->timestamp('booked_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spots');
    }
};
