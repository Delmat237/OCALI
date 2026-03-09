@extends('layouts.app')
@section('title', __('messages.reports'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.reports') }}</h1>

        @if($reports->count() > 0)
            <div class="card card-elevated" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.book') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.reported_by') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.reason') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.date') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.75rem 1rem;">{{ $report->book->title ?? '-' }}</td>
                                <td style="padding: 0.75rem 1rem;">{{ $report->user->name ?? '-' }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">{{ $report->reason }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                    <span style="padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.8rem; background: {{ $report->status === 'pending' ? 'rgba(245,158,11,0.1)' : 'rgba(16,185,129,0.1)' }}; color: {{ $report->status === 'pending' ? '#d97706' : '#059669' }};">{{ $report->status }}</span>
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">{{ $report->created_at->format('d/m/Y') }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-ghost btn-icon"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $reports->links() }}
        @else
            <p style="text-align: center; color: #94a3b8; padding: 2rem;">{{ __('messages.no_reports') }}</p>
        @endif
    </div>
</div>
@endsection
