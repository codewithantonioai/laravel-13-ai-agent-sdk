<?php

use App\Models\User;
use App\Notifications\DailySummary;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notes:send-reminders')->everyMinute();

Schedule::call(function () {
    $users = User::with(['notes' => fn ($query) => $query->whereDate('remind_at', today())])->get();

    foreach ($users as $user) {
        if ($user->notes->isNotEmpty()) {
            $user->notify(new DailySummary($user->notes));
        }
    }
})->name('daily-summary')->dailyAt('07:00');
