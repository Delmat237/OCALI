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
        .stats-grid { display: flex; gap: 1rem; margin: 1.5rem 0; }
        .stat-card { flex: 1; background-color: #f1f5f9; border-radius: 12px; padding: 1.25rem; text-align: center; }
        .stat-card .value { font-size: 1.75rem; font-weight: 800; color: #4169E1; }
        .stat-card .label { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.your_monthly_report') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $user->name }} !</h2>
            <p>{{ __('messages.monthly_report_intro', [
                'month' => date('F', mktime(0, 0, 0, $report->month, 1)),
                'year' => $report->year
            ]) }}</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="value">{{ $report->books_read }}</div>
                    <div class="label">{{ __('messages.books_read') }}</div>
                </div>
                <div class="stat-card">
                    <div class="value">{{ format_reading_time($report->reading_time_minutes) }}</div>
                    <div class="label">{{ __('messages.reading_time') }}</div>
                </div>
                @if($report->earnings > 0)
                <div class="stat-card">
                    <div class="value">{{ format_price($report->earnings) }}</div>
                    <div class="label">{{ __('messages.earnings') }}</div>
                </div>
                @endif
            </div>

            @if($report->data && isset($report->data['top_books']))
                <p style="font-weight: 600; color: #1e293b;">{{ __('messages.top_books_this_month') }}</p>
                <ul style="color: #475569; line-height: 1.8;">
                    @foreach($report->data['top_books'] as $bookTitle)
                        <li>{{ $bookTitle }}</li>
                    @endforeach
                </ul>
            @endif

            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/library') }}" class="btn">{{ __('messages.continue_reading') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
