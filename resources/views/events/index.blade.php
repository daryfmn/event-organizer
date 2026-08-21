@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Events</div>
            <div class="page-sub">{{ $totalEvents }} events total across {{ $totalTypes }} event types</div>
        </div>
        <a href="{{ route('events.create') }}" class="btn-primary">+ Create Event</a>
    </div>

    <form class="filter-bar" method="GET" action="{{ route('events.index') }}">
        <div class="filter-search">
            <svg width="13" height="13" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by event title or client..." />
        </div>
        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            @foreach ($eventTypes as $eventType)
                <option value="{{ $eventType->id }}" {{ $type == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
            @endforeach
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach (\App\Models\Event::STATUSES as $statusOption)
                <option value="{{ $statusOption }}" {{ $status === $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
            @endforeach
        </select>
        <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="oldest" {{ $sort !== 'newest' ? 'selected' : '' }}>Oldest First</option>
            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
        </select>
    </form>

    @if ($events->isEmpty())
        <div class="empty-state">No events found. Create your first event to get started.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Type</th>
                        <th>Venue</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $event->title }}</div>
                                <div class="text-muted">{{ $event->client->company_name }}</div>
                            </td>
                            <td><span class="event-type {{ $event->eventType->typeClass() }}">{{ $event->eventType->name }}</span></td>
                            <td><div class="text-muted">{{ $event->venue ?? '—' }}</div></td>
                            <td><div class="text-muted">{{ $event->dateRangeLabel() }}</div></td>
                            <td><span class="badge badge-{{ $event->status }}">{{ $event->statusLabel() }}</span></td>
                            <td>
                                <div class="event-cell">
                                    <div class="avatar-sm">{{ mb_strtoupper(mb_substr($event->createdBy->name, 0, 2)) }}</div>
                                    <span class="text-muted">{{ explode(' ', trim($event->createdBy->name))[0] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions" style="justify-content:flex-end;">
                                    <a class="icon-btn" href="{{ route('events.edit', $event) }}" title="Edit">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Delete this event?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-btn" type="submit" title="Delete">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="footer-bar">
                <span>Showing {{ $events->count() }} of {{ $totalEvents }} events</span>
            </div>
        </div>
    @endif
@endsection
