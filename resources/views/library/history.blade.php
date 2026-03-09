@extends('layouts.app')
@section('title', __('messages.reading_history'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.reading_history') }}</h1>
            <a href="{{ route('library') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> {{ __('messages.back_to_library') }}</a>
        </div>

        @if($userBooks->count() > 0)
            <div class="card card-elevated" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.book') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.progress') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.reading_time') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.last_read') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userBooks as $ub)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.75rem 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="{{ $ub->book->cover_url }}" alt="" style="width: 36px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <a href="{{ route('books.show', $ub->book) }}" style="font-weight: 600; color: inherit; text-decoration: none;">{{ $ub->book->title }}</a>
                                            <p style="font-size: 0.8rem; color: #64748b;">{{ $ub->book->author->name ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">{{ number_format($ub->progress_percent, 0) }}%</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">{{ $ub->formatted_reading_time }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">{{ $ub->last_read_at?->diffForHumans() ?? '-' }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                    <span style="padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.8rem; background: {{ $ub->status === 'completed' ? 'rgba(16,185,129,0.1)' : 'rgba(59,130,246,0.1)' }}; color: {{ $ub->status === 'completed' ? '#059669' : '#2563eb' }};">
                                        {{ $ub->status === 'completed' ? __('messages.completed') : __('messages.reading') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $userBooks->links() }}
        @else
            <p style="text-align: center; color: #94a3b8; padding: 2rem;">{{ __('messages.no_reading_history') }}</p>
        @endif
    </div>
</div>
@endsection
