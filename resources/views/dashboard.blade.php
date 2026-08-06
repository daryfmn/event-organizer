@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Dashboard</div>
            <div class="page-date">{{ now()->format('l, d F Y') }}</div>
        </div>
        <a href="#" class="btn-sm" style="padding:9px 16px;font-size:13px;">+ New Event</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <div class="stat-icon icon-blue" style="float:right;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="stat-label">Total Events</div>
                <div class="stat-value">{{ $stats['total_events'] }}</div>
                <div class="stat-sub">{{ $stats['active_this_month'] }} active this month</div>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-icon icon-sky" style="float:right;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="stat-label">Active Quotations</div>
                <div class="stat-value">{{ $stats['total_quotations'] }}</div>
                <div class="stat-sub">{{ $stats['awaiting_approval'] }} awaiting approval</div>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-icon icon-green" style="float:right;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <div class="stat-label">Total Clients</div>
                <div class="stat-value">{{ $stats['total_clients'] }}</div>
                <div class="stat-sub">{{ $stats['new_clients_this_month'] }} new this month</div>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-icon icon-amber" style="float:right;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                </div>
                <div class="stat-label">Total Vendors</div>
                <div class="stat-value">{{ $stats['total_vendors'] }}</div>
                <div class="stat-sub">Across {{ $stats['vendor_categories'] }} categories</div>
            </div>
        </div>
    </div>

    <div class="tables-grid">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Events</span>
                <button class="btn-link">View all &rarr;</button>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentEvents as $event)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $event->title }}</div>
                                    <div class="text-muted">{{ $event->client?->company_name }}</div>
                                </td>
                                <td><span class="event-type {{ $event->eventType?->typeClass() }}">{{ $event->eventType?->name }}</span></td>
                                <td><div class="text-muted">{{ $event->start_date?->format('d M Y') }}</div></td>
                                <td>
                                    <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-title">No events yet</div>
                                        Create your first event to get started.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Quotations</span>
                <button class="btn-link">View all &rarr;</button>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Quotation No.</th>
                            <th>Event</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentQuotations as $quotation)
                            <tr>
                                <td>
                                    <div class="font-medium" style="font-size:12px;">{{ $quotation->quotation_number }}</div>
                                    @if ($quotation->variant_label)
                                        <div class="text-muted">{{ $quotation->variant_label }}</div>
                                    @endif
                                </td>
                                <td><div style="font-size:12px;">{{ $quotation->event?->title }}</div></td>
                                <td><div class="rupiah">{{ \App\Support\Money::rupiah($quotation->grand_total) }}</div></td>
                                <td>
                                    <span class="badge badge-{{ $quotation->event?->status }}">{{ ucfirst($quotation->event?->status ?? '') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-title">No quotations yet</div>
                                        Quotations appear here once created.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
