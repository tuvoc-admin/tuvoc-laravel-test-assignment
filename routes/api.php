<?php

use App\Http\Controllers\Api\StudentAvailabilityController;
use App\Http\Controllers\Api\StudentSessionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'login']);

Route::middleware('api:userAuth')->group(function () {
    Route::get('students', [StudentController::class, 'index']);
    Route::post('students', [StudentController::class, 'store']);

    Route::post('/updateAvailability', [StudentAvailabilityController::class, 'store']);

    Route::post('/scheduleSession', [StudentSessionController::class, 'scheduleSession']);
    Route::post('/rateSession', [StudentSessionController::class, 'rateSession']);
});