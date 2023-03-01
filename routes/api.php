<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/createLead', [\App\Http\Controllers\SolarEndPointsController::class, 'createLead']);
Route::post('/updateLead{id?}', [\App\Http\Controllers\SolarEndPointsController::class, 'updateLead']);
Route::post('/deleteLead/{id?}', [\App\Http\Controllers\SolarEndPointsController::class, 'deleteLead']);
Route::post('/readLead/{id?}', [\App\Http\Controllers\SolarEndPointsController::class, 'readLead']);
Route::post('/getQualityLead/{quality?}', [\App\Http\Controllers\SolarEndPointsController::class, 'getQualityLead']);
