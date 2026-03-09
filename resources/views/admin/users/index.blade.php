@extends('layouts.app')
@section('title', __('messages.manage_users'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.manage_users') }}</h1>
        <div class="card card-elevated" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.user') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.role') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.joined') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $user->avatar_url }}" alt="" style="width: 32px; height: 32px; border-radius: 50%;">
                                    <div>
                                        <div style="font-weight: 600;">{{ $user->name }}</div>
                                        <div style="font-size: 0.8rem; color: #64748b;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $user->role_label }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; display: inline-block; background: {{ $user->is_active ? '#10b981' : '#ef4444' }};"></span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-icon"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display: inline;">@csrf
                                        <button class="btn btn-ghost btn-icon"><i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection
