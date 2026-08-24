@extends('layouts.app')

@php
    $coverage = $quotation->variant_start_date?->isSameDay($quotation->variant_end_date)
        ? $quotation->variant_start_date->format('d M Y')
        : $quotation->variant_start_date->format('d M').' – '.$quotation->variant_end_date->format('d M Y');
@endphp

@section('title', $quotation->quotation_number)

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">{{ $quotation->quotation_number }}</div>
            <div class="page-sub">Quotation detail</div>
        </div>
        <a href="{{ route('quotations.index') }}" class="btn-outline">Back to Quotations</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <div class="card-title">{{ $quotation->variant_label }}</div>
                <div class="card-desc">{{ $coverage }} @if ($quotation->is_approved)<span class="badge badge-approved" style="margin-left:8px;">Winning Variant</span>@endif</div>
            </div>
            <span class="badge badge-{{ $quotation->event->status }}">{{ $quotation->event->statusLabel() }}</span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Event</div>
                    <div class="dd">{{ $quotation->event->title }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Client</div>
                    <div class="dd">{{ $quotation->event->client->company_name }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Event Type</div>
                    <div class="dd">{{ $quotation->event->eventType->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Event Dates</div>
                    <div class="dd">{{ $quotation->event->dateRangeLabel() }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Signed By</div>
                    <div class="dd">{{ $quotation->signedBy?->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Created</div>
                    <div class="dd">{{ $quotation->created_at?->format('d M Y') }}</div>
                </div>
            </div>

            @if ($quotation->notes)
                <div class="detail-item">
                    <div class="dt">Notes</div>
                    <div class="notes-box">{{ $quotation->notes }}</div>
                </div>
            @endif

            @foreach ($groupedItems as $section => $items)
                <div class="section-block">
                    <div class="section-head">
                        <span>{{ \App\Models\QuotationItem::SECTION_LABELS[$section] }} <span class="text-muted">({{ $items->count() }} item{{ $items->count() > 1 ? 's' : '' }})</span></span>
                        <span class="section-subtotal">{{ \App\Models\Quotation::rupiah($items->sum('total')) }}</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Detail</th>
                                <th style="text-align:right;">Qty</th>
                                <th>Unit</th>
                                <th style="text-align:right;">Length</th>
                                <th>Length Unit</th>
                                <th style="text-align:right;">Price</th>
                                <th>Vendor</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="font-medium">{{ $item->description }}</div>
                                        @if ($item->sub_section)
                                            <div class="text-muted">{{ $item->sub_section }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $item->item_detail ?: '—' }}</td>
                                    <td style="text-align:right;">{{ $item->qty }}</td>
                                    <td>{{ $item->unit ?: '—' }}</td>
                                    <td style="text-align:right;">{{ $item->length }}</td>
                                    <td>{{ $item->length_unit ?: '—' }}</td>
                                    <td style="text-align:right;" class="rupiah">{{ \App\Models\Quotation::rupiah($item->price) }}</td>
                                    <td>
                                        @if ($item->vendor)
                                            {{ $item->vendor->name }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;" class="rupiah font-medium">{{ \App\Models\Quotation::rupiah($item->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach

            <div class="totals-box">
                <div class="totals-row"><span>Subtotal</span><span>{{ \App\Models\Quotation::rupiah($quotation->subtotal) }}</span></div>
                <div class="totals-row"><span>Organizing fee ({{ $quotation->organizing_fee_percent }}%)</span><span>{{ \App\Models\Quotation::rupiah($quotation->organizing_fee) }}</span></div>
                <div class="totals-row"><span>Total</span><span>{{ \App\Models\Quotation::rupiah($quotation->total) }}</span></div>
                <div class="totals-row"><span>PPN ({{ $quotation->ppn_percent }}%)</span><span>{{ \App\Models\Quotation::rupiah($quotation->ppn) }}</span></div>
                <div class="totals-row grand"><span>Grand Total</span><span>{{ \App\Models\Quotation::rupiah($quotation->grand_total) }}</span></div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('quotations.index') }}" class="btn-outline">Back</a>
            <div class="footer-actions">
                <form method="POST" action="{{ route('quotations.approve', $quotation) }}">
                    @csrf
                    <button type="submit" class="btn-outline">{{ $quotation->is_approved ? 'Remove Approval' : 'Approve as Winning' }}</button>
                </form>
                <a href="{{ route('quotations.edit', $quotation) }}" class="btn-primary">Edit Quotation</a>
            </div>
        </div>
    </div>
@endsection
