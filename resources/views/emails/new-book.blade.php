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
        .book-card { background-color: #f1f5f9; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; display: flex; gap: 1rem; }
        .book-cover { width: 80px; height: 120px; border-radius: 8px; object-fit: cover; background-color: #e2e8f0; }
        .book-info h3 { color: #1e293b; margin-bottom: 0.25rem; }
        .book-info p { margin: 0.25rem 0; font-size: 0.9rem; }
        .email-footer { background-color: #f8fafc; padding: 1.5rem 2rem; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #94a3b8; font-size: 0.85rem; margin: 0.25rem 0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>{{ __('messages.new_book_available') }}</h1>
        </div>
        <div class="email-body">
            <h2>{{ __('messages.hello') }}, {{ $user->name }} !</h2>
            <p>{{ __('messages.new_book_email_intro') }}</p>

            <div class="book-card">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="book-cover">
                @endif
                <div class="book-info">
                    <h3>{{ $book->title }}</h3>
                    <p><strong>{{ __('messages.author') }}:</strong> {{ $book->author->name }}</p>
                    <p><strong>{{ __('messages.category') }}:</strong> {{ $book->category->localized_name }}</p>
                    @if($book->description)
                        <p>{{ Str::limit($book->description, 120) }}</p>
                    @endif
                </div>
            </div>

            <p style="text-align: center; margin: 1.5rem 0;">
                <a href="{{ url('/books/' . $book->slug) }}" class="btn">{{ __('messages.discover_book') }}</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OCaLi. {{ __('messages.all_rights_reserved') }}</p>
            <p>{{ __('messages.email_sent_to') }} {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
