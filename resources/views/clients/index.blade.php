@extends('layouts.app')

@section('title', 'Clients')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Clients</div>
            <div class="page-sub">{{ $clients->count() }} companies your team has organized events for</div>
        </div>
        <a href="{{ route('clients.create') }}" class="btn-primary">+ Add Client</a>
    </div>

    <form class="filter-bar" method="GET" action="{{ route('clients.index') }}">
        <div class="filter-search">
            <svg width="13" height="13" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by company or PIC name..." />
        </div>
    </form>

    @if ($clients->isEmpty())
        <div class="empty-state">No clients found. Add your first client to get started.</div>
    @else
        <div class="client-grid">
            @foreach ($clients as $client)
                @php $logoClass = ['', 'c-purple', 'c-green'][$loop->index % 3]; @endphp
                <div class="client-card">
                    <div class="client-card-top">
                        <div class="client-logo {{ $logoClass }}">{{ $client->initials() }}</div>
                        <div>
                            <div class="client-name">{{ $client->company_name }}</div>
                            <div class="client-pic">PIC: {{ $client->pic_name }}</div>
                        </div>
                    </div>
                    <div class="client-divider"></div>
                    @if ($client->phone)
                        <div class="client-info-row">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $client->phone }}
                        </div>
                    @endif
                    @if ($client->email)
                        <div class="client-info-row">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4z" opacity="0"/><path d="M22 6l-10 7L2 6"/><path d="M2 6h20v12H2z"/></svg>
                            {{ $client->email }}
                        </div>
                    @endif
                    <div class="client-footer">
                        <span class="event-count"><b>{{ $client->events_count }}</b> {{ Str::plural('event', $client->events_count) }}</span>
                        <div class="card-actions">
                            <a class="icon-btn" href="{{ route('clients.edit', $client) }}" title="Edit">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Delete this client?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="icon-btn" type="submit" title="Delete">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
