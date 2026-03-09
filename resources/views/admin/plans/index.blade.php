@extends('layouts.app')
@section('title', __('messages.manage_plans'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.manage_plans') }}</h1>
            <a href="{{ route('admin.plans.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('messages.new_plan') }}</a>
        </div>

        <div class="card card-elevated" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.name') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.type') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.duration') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ __('messages.price') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ $plan->localized_name }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ ucfirst($plan->type) }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $plan->duration_label }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">{{ $plan->formatted_price }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; display: inline-block; background: {{ $plan->is_active ? '#10b981' : '#ef4444' }};"></span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-ghost btn-icon"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
