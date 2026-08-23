@extends('layouts.app')

@section('title', 'Quotations')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Quotations</div>
            <div class="page-sub">Every price offer sent across all events</div>
        </div>
        <a href="{{ route('quotations.create') }}" class="btn-primary">+ Create Quotation</a>
    </div>

    <div class="summary-strip">
        <div class="summary-item">
            <div class="summary-label">Total Quotations</div>
            <div class="summary-value">{{ $totalQuotations }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Awaiting Approval</div>
            <div class="summary-value amber">{{ $awaitingApproval }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Approved</div>
            <div class="summary-value green">{{ $approvedCount }}</div>
        </div>
    </div>

    <form class="filter-bar" method="GET" action="{{ route('quotations.index') }}">
        <div class="filter-search">
            <svg width="13" height="13" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by quotation number or event..." />
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach (\App\Models\Event::STATUSES as $statusOption)
                <option value="{{ $statusOption }}" {{ $status === $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
            @endforeach
        </select>
        <select name="event" class="filter-select" onchange="this.form.submit()">
            <option value="">All Events</option>
            @foreach ($events as $event)
                <option value="{{ $event->id }}" {{ $eventFilter == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
            @endforeach
        </select>
        <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="newest" {{ $sort !== 'highest' ? 'selected' : '' }}>Newest First</option>
            <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Highest Total</option>
        </select>
    </form>

    @if ($quotations->isEmpty())
        <div class="empty-state">No quotations found. Create your first quotation to get started.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Quotation No.</th>
                        <th>Event</th>
                        <th>Coverage</th>
                        <th style="text-align:right;">Grand Total</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotations as $quotation)
                        <tr>
                            <td>
                                <a class="font-medium" href="{{ route('quotations.show', $quotation) }}" style="color:inherit;">{{ $quotation->quotation_number }}</a>
                                @if ($quotation->variant_label)
                                    <span class="variant-tag">{{ $quotation->variant_label }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium" style="font-size:12.5px;">{{ $quotation->event->title }}</div>
                                <div class="text-muted">{{ $quotation->event->client->company_name }}</div>
                            </td>
                            <td><div class="text-muted">{{ $quotation->variant_start_date?->isSameDay($quotation->variant_end_date) ? $quotation->variant_start_date->format('d M Y') : $quotation->variant_start_date->format('d M').' – '.$quotation->variant_end_date->format('d M Y') }}</div></td>
                            <td style="text-align:right;"><div class="rupiah">{{ \App\Models\Quotation::rupiah($quotation->grand_total) }}</div></td>
                            <td><span class="badge badge-{{ $quotation->event->status }}">{{ $quotation->event->statusLabel() }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a class="icon-btn" href="{{ route('quotations.show', $quotation) }}" title="View detail">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('quotations.approve', $quotation) }}" title="{{ $quotation->is_approved ? 'Unapprove' : 'Approve as winning variant' }}">
                                        @csrf
                                        <button class="icon-btn {{ $quotation->is_approved ? 'icon-btn-active' : '' }}" type="submit">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                    <a class="icon-btn" href="{{ route('quotations.edit', $quotation) }}" title="Edit">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('quotations.destroy', $quotation) }}" onsubmit="return confirm('Delete this quotation?');" style="display:inline;">
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
                <span>Showing {{ $quotations->count() }} of {{ $totalQuotations }} quotations</span>
            </div>
        </div>
    @endif
@endsection
