<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .email-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 2rem; text-align: center; }
        .email-header h1 { color: #ffffff; font-size: 1.5rem; }
        .email-body { padding: 2rem; }
        .email-body h2 { color: #1e293b; font-size: 1.25rem; margin-bottom: 1rem; }
        .email-body p { color: #475569; line-height: 1.6; margin-bottom: 1rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #FFA500 0%, #FF6B00 100%); color: #ffffff; text-decoration: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; }
        .earning-box { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 12px; padding: 2rem; margin: 1.5rem 0; text-align: center; }
        .earning-box .amount { font-size: 2.5rem; font-weight: 800; color: #059669; }
        .earning-box .label { color: #065f46; font-size: 0.9rem; margin-top: 0.25rem; }
        .book-ref { background-color: #f1f5f9; border-radius: 8px; padding: 1rem; margin: 1rem 0; }
        .book-ref strong { color: #1e293b; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.new_earning') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $author->name }} !</h2>
            <p>{{ __('messages.earning_notification_intro') }}</p>

            <div class="earning-box">
                <div class="amount">{{ format_price($amount) }}</div>
                <div class="label">{{ __('messages.credited_to_wallet') }}</div>
            </div>

            <div class="book-ref">
                <strong>{{ __('messages.book') }}:</strong> {{ $book->title }}
            </div>

            <p>{{ __('messages.earning_notification_details') }}</p>

            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/wallet') }}" class="btn">{{ __('messages.view_wallet') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $author->email }}</p>
        </div>
    </div>
</body>
</html>
