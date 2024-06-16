<?php

use App\Enums\SpotType;
use App\Models\ParkingSpot;
use App\Models\User;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->nullable();
            $table->string('vehicle_license',15)->nullable();
            $table->enum('spot_type', array_column(SpotType::cases(), 'value'))->default(null);
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
