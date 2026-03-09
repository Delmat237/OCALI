@extends('layouts.app')
@section('title', __('messages.manage_categories'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.manage_categories') }}</h1>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('messages.new_category') }}</a>
        </div>
        <div class="card card-elevated" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.name') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.books') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">
                                @if($cat->icon)<i class="fas {{ $cat->icon }}" style="color: {{ $cat->color ?? 'var(--blue-roi)' }};"></i>@endif
                                {{ $cat->localized_name }}
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $cat->books_count }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; display: inline-block; background: {{ $cat->is_active ? '#10b981' : '#ef4444' }};"></span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-ghost btn-icon"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">@csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-icon" style="color: #ef4444;"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
