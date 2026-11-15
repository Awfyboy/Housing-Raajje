<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\User;

Schedule::call(function () {
    User::whereNull('email_verified_at')
        ->where('created_at', '<', now()->subMinutes(10))
        ->delete();
})->everyMinute();