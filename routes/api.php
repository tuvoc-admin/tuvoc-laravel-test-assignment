<?php

use App\Http\Controllers\Api\StudentAvailabilityController;
use App\Http\Controllers\Api\StudentSessionController;
<<<<<<< HEAD
use App\Http\Controllers\Api\DocParserController;
=======
use App\Http\Controllers\Api\StudentReportController;
>>>>>>> vanraj-dev
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('students', [StudentController::class, 'index']);
    Route::post('students', [StudentController::class, 'store']);

    Route::post('/updateAvailability', [StudentAvailabilityController::class, 'store']);

    Route::post('/scheduleSession', [StudentSessionController::class, 'scheduleSession']);
    Route::post('/rateSession', [StudentSessionController::class, 'rateSession']);

    Route::post('/parceDocx', [DocParserController::class, 'parse']);
    
    Route::post('/generatereports', [StudentReportController::class, 'generateOrUpdateReport']);
});
