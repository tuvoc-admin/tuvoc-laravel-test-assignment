<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'login']);

Route::middleware('api:userAuth')->group(function () {
    Route::get('listStudents', [StudentController::class, 'index']);
    Route::post('addStudent', [StudentController::class, 'store']);
});