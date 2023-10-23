<?php

use App\Http\Controllers\api\{
    Auth\AuthController,
    Event\EventController,
    User\UserController,
    Registration\RegistrationController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/event', [EventController::class, 'store']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/event/{id}', [EventController::class, 'show']);
    Route::put('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);

    Route::post('/user', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);

    Route::post('/registration', [RegistrationController::class, 'store']);
    Route::get('/registrations', [RegistrationController::class, 'index']);
    Route::get('/registration/{id}', [RegistrationController::class, 'show']);
    Route::put('/registration/{id}', [RegistrationController::class, 'update']);

});

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);



});


Route::post('/login', [AuthController::class, 'login']);
