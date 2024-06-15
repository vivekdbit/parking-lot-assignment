<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingSpotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health Check
Route::get('/health', function () {
    return response()->json(['message' => 'Hello World'], 200);
});

Route::group([
    'prefix' => 'v1',
], function () {
    Route::prefix('parking-spot')->group(function () {
        Route::post('{id}/park', [ParkingSpotController::class,'park']);
        Route::post('{id}/unpark', [ParkingSpotController::class,'unpark']);
    });
    Route::get('parking-lot/{id}', [ParkingSpotController::class,'getAvailability']);
});