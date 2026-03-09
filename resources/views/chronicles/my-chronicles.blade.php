@extends('layouts.app')
@section('title', __('messages.my_chronicles'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.my_chronicles') }}</h1>
            <a href="{{ route('author.chronicles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('messages.new_chronicle') }}</a>
        </div>

        @if($chronicles->count() > 0)
            <div style="display: grid; gap: 1rem;">
                @foreach($chronicles as $chronicle)
                    <div class="card card-elevated" style="display: flex; gap: 1rem; padding: 1.25rem;">
                        <div style="flex: 1;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $chronicle->title }}</h3>
                            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">{{ Str::limit($chronicle->excerpt, 100) }}</p>
                            <div style="display: flex; gap: 1rem; font-size: 0.8rem; color: #94a3b8;">
                                <span>{{ $chronicle->published_at?->format('d/m/Y') }}</span>
                                <span><i class="fas fa-eye"></i> {{ $chronicle->views_count }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem; align-items: start;">
                            <a href="{{ route('author.chronicles.edit', $chronicle) }}" class="btn btn-ghost btn-icon"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('author.chronicles.destroy', $chronicle) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-icon" style="color: #ef4444;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $chronicles->links() }}
        @else
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-pen-fancy" style="font-size: 4rem; color: #d1d5db;"></i>
                <h3 style="color: #64748b; margin-top: 1rem;">{{ __('messages.no_chronicles') }}</h3>
                <a href="{{ route('author.chronicles.create') }}" class="btn btn-primary" style="margin-top: 1rem;">{{ __('messages.write_first_chronicle') }}</a>
            </div>
        @endif
    </div>
</div>
@endsection
