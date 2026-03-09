<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .email-header { background: linear-gradient(135deg, #FFA500 0%, #FF6B00 100%); padding: 2rem; text-align: center; }
        .email-header h1 { color: #ffffff; font-size: 1.5rem; }
        .email-body { padding: 2rem; }
        .email-body h2 { color: #1e293b; font-size: 1.25rem; margin-bottom: 1rem; }
        .email-body p { color: #475569; line-height: 1.6; margin-bottom: 1rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #4169E1 0%, #2E4BC9 100%); color: #ffffff; text-decoration: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; }
        .plan-details { background-color: #f1f5f9; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; }
        .plan-details table { width: 100%; }
        .plan-details td { padding: 0.5rem 0; color: #475569; }
        .plan-details td:first-child { font-weight: 600; color: #1e293b; }
        .success-badge { background-color: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 8px; display: inline-block; font-weight: 600; margin-bottom: 1rem; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.subscription_confirmed') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $user->name }} !</h2>
            <span class="success-badge">&#10003; {{ __('messages.payment_received') }}</span>
            <p>{{ __('messages.subscription_confirmed_intro') }}</p>

            <div class="plan-details">
                <table>
                    <tr>
                        <td>{{ __('messages.plan') }}</td>
                        <td>{{ $plan->name }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.type') }}</td>
                        <td>{{ ucfirst($plan->type) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.duration') }}</td>
                        <td>{{ $plan->duration_label }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.price') }}</td>
                        <td>{{ format_price($plan->price) }}</td>
                    </tr>
                    @if($plan->type === 'reader')
                    <tr>
                        <td>{{ __('messages.books_included') }}</td>
                        <td>{{ $plan->books_limit }} {{ __('messages.books') }}</td>
                    </tr>
                    @elseif($plan->type === 'author')
                    <tr>
                        <td>{{ __('messages.publications_included') }}</td>
                        <td>{{ $plan->books_limit }} {{ __('messages.publications') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>{{ __('messages.start_date') }}</td>
                        <td>{{ $subscription->starts_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.end_date') }}</td>
                        <td>{{ $subscription->ends_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>

            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/dashboard') }}" class="btn">{{ __('messages.go_to_dashboard') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
