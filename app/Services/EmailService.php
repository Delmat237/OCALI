<?php

namespace App\Services;

use App\Models\Book;
use App\Models\MonthlyReport;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Envoyer un email de bienvenue après inscription
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            Mail::send('emails.welcome', [
                'user' => $user,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject(__('messages.welcome_email_subject'));
            });

            Log::info('Welcome email sent', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Welcome email failed: ' . $e->getMessage(), ['user_id' => $user->id]);
            return false;
        }
    }

    /**
     * Envoyer une notification de nouveau livre publié
     */
    public function sendNewBookNotification(Book $book, User $subscriber): bool
    {
        try {
            Mail::send('emails.new-book', [
                'user' => $subscriber,
                'book' => $book,
            ], function ($message) use ($subscriber, $book) {
                $message->to($subscriber->email, $subscriber->name)
                    ->subject(__('messages.new_book_email_subject', ['title' => $book->title]));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('New book notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer une confirmation d'abonnement
     */
    public function sendSubscriptionConfirmation(Subscription $subscription): bool
    {
        try {
            $user = $subscription->user;
            $plan = $subscription->plan;

            Mail::send('emails.subscription-confirmed', [
                'user' => $user,
                'subscription' => $subscription,
                'plan' => $plan,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject(__('messages.subscription_confirmed_subject'));
            });

            Log::info('Subscription confirmation sent', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Subscription confirmation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un rappel d'expiration d'abonnement
     */
    public function sendSubscriptionExpirationReminder(Subscription $subscription): bool
    {
        try {
            $user = $subscription->user;

            Mail::send('emails.subscription-expiring', [
                'user' => $user,
                'subscription' => $subscription,
                'daysRemaining' => $subscription->days_remaining,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject(__('messages.subscription_expiring_subject'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Subscription expiration reminder failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer le rapport mensuel à un lecteur
     */
    public function sendMonthlyReport(MonthlyReport $report): bool
    {
        try {
            $user = $report->user;

            Mail::send('emails.monthly-report', [
                'user' => $user,
                'report' => $report,
            ], function ($message) use ($user, $report) {
                $monthName = date('F Y', mktime(0, 0, 0, $report->month, 1, $report->year));
                $message->to($user->email, $user->name)
                    ->subject(__('messages.monthly_report_subject', ['month' => $monthName]));
            });

            $report->update([
                'email_sent' => true,
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Monthly report email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer une notification de gain pour un auteur
     */
    public function sendEarningNotification(User $author, float $amount, Book $book): bool
    {
        try {
            Mail::send('emails.author-earning', [
                'author' => $author,
                'amount' => $amount,
                'book' => $book,
            ], function ($message) use ($author) {
                $message->to($author->email, $author->name)
                    ->subject(__('messages.earning_notification_subject'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Earning notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer une confirmation de retrait
     */
    public function sendWithdrawalConfirmation(User $user, float $amount, string $method): bool
    {
        try {
            Mail::send('emails.withdrawal-confirmed', [
                'user' => $user,
                'amount' => $amount,
                'method' => $method,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject(__('messages.withdrawal_confirmed_subject'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Withdrawal confirmation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer la newsletter
     */
    public function sendNewsletter(string $subject, string $content, array $subscribers): int
    {
        $sent = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::send('emails.newsletter', [
                    'content' => $content,
                    'subscriber' => $subscriber,
                ], function ($message) use ($subscriber, $subject) {
                    $email = is_array($subscriber) ? $subscriber['email'] : $subscriber->email;
                    $name = is_array($subscriber) ? ($subscriber['name'] ?? '') : $subscriber->name;

                    $message->to($email, $name)
                        ->subject($subject);
                });

                $sent++;
            } catch (\Exception $e) {
                Log::error('Newsletter send failed: ' . $e->getMessage());
            }
        }

        return $sent;
    }
}
