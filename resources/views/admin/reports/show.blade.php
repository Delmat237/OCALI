@extends('layouts.app')
@section('title', __('messages.report_details'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 700px;">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-ghost" style="margin-bottom: 1rem;"><i class="fas fa-arrow-left"></i> {{ __('messages.back') }}</a>

        <div class="card card-elevated" style="padding: 2rem;">
            <h2 style="margin-bottom: 1rem;">{{ __('messages.report_details') }}</h2>

            <div style="margin-bottom: 1.5rem;">
                <p><strong>{{ __('messages.book') }}:</strong> {{ $report->book->title ?? '-' }}</p>
                <p><strong>{{ __('messages.author') }}:</strong> {{ $report->book->author->name ?? '-' }}</p>
                <p><strong>{{ __('messages.reported_by') }}:</strong> {{ $report->user->name ?? '-' }}</p>
                <p><strong>{{ __('messages.reason') }}:</strong> {{ $report->reason }}</p>
                <p><strong>{{ __('messages.description') }}:</strong></p>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 0.5rem;">
                    {{ $report->description }}
                </div>
            </div>

            @if($report->status === 'pending')
                <form action="{{ route('admin.reports.handle', $report) }}" method="POST">
                    @csrf @method('PUT')
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.action') }}</label>
                        <select name="action" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            <option value="dismiss">{{ __('messages.dismiss') }}</option>
                            <option value="warn_author">{{ __('messages.warn_author') }}</option>
                            <option value="remove_book">{{ __('messages.remove_book') }}</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.admin_notes') }}</label>
                        <textarea name="admin_notes" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.handle_report') }}</button>
                </form>
            @else
                <p style="color: #10b981;"><strong>{{ __('messages.handled') }}:</strong> {{ $report->admin_action }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
