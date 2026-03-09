@extends('layouts.app')
@section('title', __('messages.notifications'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 800px;">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.notifications') }}</h1>
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ghost">{{ __('messages.mark_all_read') }}</button>
                </form>
            @endif
        </div>

        @forelse($notifications as $notification)
            <div class="card" style="padding: 1rem 1.5rem; margin-bottom: 0.75rem; {{ !$notification->read_at ? 'border-left: 4px solid var(--orange-fluo); background: rgba(255,165,0,0.03);' : '' }}">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="font-weight: {{ !$notification->read_at ? '600' : '400' }};">{{ $notification->data['message'] ?? '' }}</p>
                        <small style="color: #94a3b8;">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-icon" title="{{ __('messages.mark_read') }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 3rem;">
                <i class="fas fa-bell-slash" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <p style="color: #94a3b8;">{{ __('messages.no_notifications') }}</p>
            </div>
        @endforelse

        {{ $notifications->links() }}
    </div>
</div>
@endsection
