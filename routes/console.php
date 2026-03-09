<?php

use App\Models\MonthlyReport;
use App\Models\Subscription;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

// Expire subscriptions
Schedule::call(function () {
    Subscription::where('status', 'active')
        ->where('ends_at', '<=', now())
        ->update(['status' => 'expired']);
})->daily();

// Send subscription expiration reminders (3 days before)
Schedule::call(function () {
    $emailService = new EmailService();

    $expiringSoon = Subscription::where('status', 'active')
        ->whereBetween('ends_at', [now()->addDays(2), now()->addDays(4)])
        ->with('user')
        ->get();

    foreach ($expiringSoon as $subscription) {
        $emailService->sendSubscriptionExpirationReminder($subscription);
    }
})->daily();

// Generate monthly reports (first day of each month)
Schedule::call(function () {
    $lastMonth = now()->subMonth();
    $year = $lastMonth->year;
    $month = $lastMonth->month;

    User::chunk(100, function ($users) use ($year, $month) {
        foreach ($users as $user) {
            // Check if report already exists
            if (!MonthlyReport::where('user_id', $user->id)
                ->where('year', $year)
                ->where('month', $month)
                ->exists()) {

                // Create report based on user type
                $report = MonthlyReport::create([
                    'user_id' => $user->id,
                    'year' => $year,
                    'month' => $month,
                    'books_read' => $user->userBooks()
                        ->whereMonth('completed_at', $month)
                        ->whereYear('completed_at', $year)
                        ->where('status', 'completed')
                        ->count(),
                    'reading_time_minutes' => $user->readingSessions()
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->sum('duration_minutes'),
                    'earnings' => $user->wallet?->transactions()
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->where('type', 'earning')
                        ->sum('amount') ?? 0,
                ]);

                // Send email
                $emailService = new EmailService();
                $emailService->sendMonthlyReport($report);
            }
        }
    });
})->monthlyOn(1, '08:00');

// Newsletter (weekly)
Schedule::call(function () {
    // This would be triggered by admin from dashboard
    // Automatic weekly newsletter can be configured here
})->weeklyOn(1, '10:00');
