<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users/score', [UserController::class, 'addScore']);
Route::get('/leaderboard', [UserController::class, 'getLeaderboard']);


Route::get('/send-notification', [UserController::class, 'sendNotification']);
Route::get('/send-notifications', [UserController::class, 'sendNotifications']);
