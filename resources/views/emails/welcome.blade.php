<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .email-header { background: linear-gradient(135deg, #4169E1 0%, #2E4BC9 100%); padding: 2rem; text-align: center; }
        .email-header img { max-height: 50px; }
        .email-header h1 { color: #ffffff; font-size: 1.5rem; margin-top: 1rem; }
        .email-body { padding: 2rem; }
        .email-body h2 { color: #1e293b; font-size: 1.25rem; margin-bottom: 1rem; }
        .email-body p { color: #475569; line-height: 1.6; margin-bottom: 1rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #FFA500 0%, #FF6B00 100%); color: #ffffff; text-decoration: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; }
        .features { background-color: #f1f5f9; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; }
        .features li { color: #475569; padding: 0.25rem 0; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.welcome_to_ocali') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $user->name }} !</h2>
            <p>{{ __('messages.welcome_email_intro') }}</p>

            <div class="features">
                <p style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">{{ __('messages.what_you_can_do') }}</p>
                <ul>
                    <li>{{ __('messages.welcome_feature_1') }}</li>
                    <li>{{ __('messages.welcome_feature_2') }}</li>
                    <li>{{ __('messages.welcome_feature_3') }}</li>
                    <li>{{ __('messages.welcome_feature_4') }}</li>
                </ul>
            </div>

            <p>{{ __('messages.welcome_email_cta_text') }}</p>
            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/explore') }}" class="btn">{{ __('messages.explore_books') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
