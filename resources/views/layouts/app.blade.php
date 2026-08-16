<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Dashboard' }} — nevermind.hcn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@php
    $user = auth()->user();
    $nameParts = explode(' ', $user->name ?? '');
    $initials = strtoupper(
        mb_substr($nameParts[0] ?? '', 0, 1) .
        mb_substr($nameParts[1] ?? '', 0, 1)
    );
    $nav = [
        ['label' => 'Dashboard', 'href' => route('dashboard'), 'route' => 'dashboard', 'icon' => 'grid'],
        ['label' => 'Events', 'href' => '#', 'route' => '', 'icon' => 'calendar'],
        ['label' => 'Quotations', 'href' => '#', 'route' => '', 'icon' => 'file'],
        ['label' => 'Client', 'href' => route('clients.index'), 'route' => 'clients.*', 'icon' => 'users'],
        ['label' => 'Vendors', 'href' => route('vendors.index'), 'route' => 'vendors.*', 'icon' => 'home'],
    ];
    $icons = [
        'grid' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>',
        'calendar' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'file' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
        'users' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'home' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
        'settings' => '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
    ];
@endphp

<aside class="sidebar">
    <a class="sidebar-logo" href="{{ route('dashboard') }}">nevermind<span>.</span>hcn</a>

    <nav class="sidebar-nav">
        @foreach ($nav as $item)
            <a class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ $item['href'] }}">
                {!! $icons[$item['icon']] !!}
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="sidebar-divider"></div>

        <a class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="#">
            {!! $icons['settings'] !!}
            Settings
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="nav-item">
            <div class="avatar-sm" style="width:28px;height:28px;background:#38BDF8;color:#0F172A;">{{ $initials }}</div>
            <div>
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-role">{{ $user->job_title ?? ucfirst($user->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Log out</button>
        </form>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-search">
            <svg width="14" height="14" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search events, quotations, clients..." />
        </div>
        <div class="topbar-right">
            <div class="topbar-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <div class="avatar">{{ $initials }}</div>
        </div>
    </header>

    <main class="content">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('updated'))
            <div class="alert alert-warning">{{ session('updated') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
