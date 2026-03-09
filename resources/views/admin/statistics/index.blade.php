@extends('layouts.app')
@section('title', __('messages.statistics'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.statistics') }}</h1>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div class="card card-elevated" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem;">{{ __('messages.top_books') }}</h3>
                @foreach($topBooks as $i => $book)
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 800; color: var(--blue-roi); width: 24px;">{{ $i + 1 }}</span>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $book->title }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $book->reads_count }} {{ __('messages.reads') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card card-elevated" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem;">{{ __('messages.top_authors') }}</h3>
                @foreach($topAuthors as $i => $author)
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 800; color: var(--orange-fluo); width: 24px;">{{ $i + 1 }}</span>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $author->name }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $author->books_count }} {{ __('messages.books') }} - {{ number_format($author->books_sum_reads_count ?? 0) }} {{ __('messages.reads') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card card-elevated" style="padding: 1.5rem; margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.monthly_subscriptions') }}</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.5rem; text-align: left;">{{ __('messages.period') }}</th>
                        <th style="padding: 0.5rem; text-align: center;">{{ __('messages.subscriptions') }}</th>
                        <th style="padding: 0.5rem; text-align: right;">{{ __('messages.revenue') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlySubscriptions as $ms)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem;">{{ $ms->month }}/{{ $ms->year }}</td>
                            <td style="padding: 0.5rem; text-align: center;">{{ $ms->count }}</td>
                            <td style="padding: 0.5rem; text-align: right;">{{ number_format($ms->revenue, 0, ',', ' ') }} XAF</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
