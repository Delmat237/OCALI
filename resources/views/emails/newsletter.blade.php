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
        .email-body p { color: #475569; line-height: 1.6; margin-bottom: 1rem; }
        .newsletter-content { color: #1e293b; line-height: 1.8; margin: 1.5rem 0; }
        .newsletter-content h1, .newsletter-content h2, .newsletter-content h3 { color: #1e293b; margin: 1rem 0 0.5rem; }
        .newsletter-content p { margin-bottom: 0.75rem; }
        .newsletter-content a { color: #4169E1; }
        .newsletter-content img { max-width: 100%; border-radius: 8px; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 1.5rem 0; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
        .email-footer a { color: #4169E1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>OCaLi</h1>
        </div>
        <div class="email-body">
            @php
                $name = is_array($subscriber) ? ($subscriber['name'] ?? '') : ($subscriber->name ?? '');
            @endphp
            @if($name)
                <p>{{ __('messages.hello') }}, {{ $name }} !</p>
            @endif

            <div class="newsletter-content">
                {!! $content !!}
            </div>

            <hr class="divider">

            <p style="text-align: center;">
                <a href="{{ url('/explore') }}" style="color: #4169E1; text-decoration: none; font-weight: 600;">{{ __('messages.explore_books') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            @php
                $token = is_array($subscriber) ? ($subscriber['unsubscribe_token'] ?? '') : ($subscriber->unsubscribe_token ?? '');
            @endphp
            @if($token)
                <p><a href="{{ url('/newsletter/unsubscribe/' . $token) }}">{{ __('messages.unsubscribe') }}</a></p>
            @endif
        </div>
    </div>
</body>
</html>
