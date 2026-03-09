<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .email-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 2rem; text-align: center; }
        .email-header h1 { color: #ffffff; font-size: 1.5rem; }
        .email-body { padding: 2rem; }
        .email-body h2 { color: #1e293b; font-size: 1.25rem; margin-bottom: 1rem; }
        .email-body p { color: #475569; line-height: 1.6; margin-bottom: 1rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #FFA500 0%, #FF6B00 100%); color: #ffffff; text-decoration: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; }
        .warning-box { background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; text-align: center; }
        .warning-box .days { font-size: 2.5rem; font-weight: 800; color: #d97706; }
        .warning-box .label { color: #92400e; font-size: 0.9rem; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.subscription_expiring_title') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $user->name }} !</h2>
            <p>{{ __('messages.subscription_expiring_intro') }}</p>

            <div class="warning-box">
                <div class="days">{{ $daysRemaining }}</div>
                <div class="label">{{ __('messages.days_remaining') }}</div>
            </div>

            <p>{{ __('messages.subscription_expiring_details', [
                'plan' => $subscription->plan->name,
                'date' => $subscription->ends_at->format('d/m/Y')
            ]) }}</p>

            <p>{{ __('messages.subscription_expiring_cta') }}</p>

            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/subscriptions') }}" class="btn">{{ __('messages.renew_subscription') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
