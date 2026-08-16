@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
    @php
        $vendorIcons = [
            'multimedia' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/></svg>',
            'venue' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
            'catering' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
            'logistics' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
            '' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-6h6v6"/><path d="M3 21h18"/></svg>',
        ];
    @endphp

    <div class="page-header">
        <div>
            <div class="page-title">Vendors</div>
            <div class="page-sub">{{ $totalVendors }} suppliers across {{ $categories->count() }} categories</div>
        </div>
        <a href="{{ route('vendors.create') }}" class="btn-primary">+ Add Vendor</a>
    </div>

    <div class="category-pills">
        <a class="pill {{ $category ? '' : 'active' }}" href="{{ route('vendors.index') }}">All ({{ $totalVendors }})</a>
        @foreach ($categories as $name)
            <a class="pill {{ $category === $name ? 'active' : '' }}"
               href="{{ route('vendors.index', array_filter(['category' => $name, 'search' => $search])) }}">
                {{ $name }} ({{ $categoryCounts[$name] }})
            </a>
        @endforeach
    </div>

    <form class="filter-bar" method="GET" action="{{ route('vendors.index') }}">
        <div class="filter-search">
            <svg width="13" height="13" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by vendor name..." />
        </div>
        <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="name" {{ $sort !== 'recent' ? 'selected' : '' }}>Sort: Name A–Z</option>
            <option value="recent" {{ $sort === 'recent' ? 'selected' : '' }}>Sort: Recently Added</option>
        </select>
        @if ($category)
            <input type="hidden" name="category" value="{{ $category }}" />
        @endif
    </form>

    @if ($vendors->isEmpty())
        <div class="empty-state">No vendors found. Add your first vendor to get started.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Category</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $vendor)
                        @php $slug = $vendor->categorySlug(); @endphp
                        <tr>
                            <td>
                                <div class="vendor-cell">
                                    <div class="vendor-icon v-{{ $slug ?: 'slate' }}">
                                        {!! $vendorIcons[$slug] ?? $vendorIcons[''] !!}
                                    </div>
                                    <div class="font-medium">{{ $vendor->name }}</div>
                                </div>
                            </td>
                            <td>
                                @if ($vendor->category)
                                    <span class="category-tag {{ $slug ? 'cat-'.$slug : '' }}">{{ $vendor->category }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><div class="text-muted">{{ $vendor->phone ?? '—' }}</div></td>
                            <td><div class="text-muted">{{ $vendor->email ?? '—' }}</div></td>
                            <td>
                                <div class="row-actions" style="justify-content:flex-end;">
                                    <a class="icon-btn" href="{{ route('vendors.edit', $vendor) }}" title="Edit">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" onsubmit="return confirm('Delete this vendor?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-btn" type="submit" title="Delete">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="footer-bar">
                <span>Showing {{ $vendors->count() }} of {{ $totalVendors }} vendors</span>
            </div>
        </div>
    @endif
@endsection
