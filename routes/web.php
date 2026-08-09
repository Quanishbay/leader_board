<?php

use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/not', function () {
    $user = User::find(1);
    $user->notify(new UserNotification());
    return view('welcome');
});
