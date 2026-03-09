<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .email-header { background: linear-gradient(135deg, #4169E1 0%, #2E4BC9 100%); padding: 2rem; text-align: center; }
        .email-header h1 { color: #ffffff; font-size: 1.5rem; }
        .email-body { padding: 2rem; }
        .email-body h2 { color: #1e293b; font-size: 1.25rem; margin-bottom: 1rem; }
        .email-body p { color: #475569; line-height: 1.6; margin-bottom: 1rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #FFA500 0%, #FF6B00 100%); color: #ffffff; text-decoration: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; }
        .withdrawal-box { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 12px; padding: 2rem; margin: 1.5rem 0; text-align: center; }
        .withdrawal-box .amount { font-size: 2.5rem; font-weight: 800; color: #4169E1; }
        .withdrawal-box .label { color: #1e40af; font-size: 0.9rem; margin-top: 0.25rem; }
        .details-table { width: 100%; margin: 1.5rem 0; }
        .details-table td { padding: 0.5rem 0; color: #475569; border-bottom: 1px solid #f1f5f9; }
        .details-table td:first-child { font-weight: 600; color: #1e293b; }
        .success-badge { background-color: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 8px; display: inline-block; font-weight: 600; margin-bottom: 1rem; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.withdrawal_confirmed') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $user->name }} !</h2>
            <span class="success-badge">&#10003; {{ __('messages.withdrawal_processed') }}</span>
            <p>{{ __('messages.withdrawal_confirmed_intro') }}</p>

            <div class="withdrawal-box">
                <div class="amount">{{ format_price($amount) }}</div>
                <div class="label">{{ __('messages.amount_sent') }}</div>
            </div>

            <table class="details-table">
                <tr>
                    <td>{{ __('messages.payment_method') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
                </tr>
                <tr>
                    <td>{{ __('messages.date') }}</td>
                    <td>{{ now()->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            <p>{{ __('messages.withdrawal_confirmed_note') }}</p>

            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/wallet') }}" class="btn">{{ __('messages.view_wallet') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
