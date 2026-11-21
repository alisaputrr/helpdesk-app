<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Helpdesk') - Helpdesk Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/helpdesk.css') }}">
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">H</div>
            <div><b>Helpdesk</b><small>Kampus Digital</small></div>
        </div>

        

        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="ico">⌂</span> Dashboard</a>
            <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.index', 'tickets.show', 'tickets.edit') ? 'active' : '' }}"><span class="ico">▤</span> Semua Tiket</a>
            <a href="{{ route('tickets.create') }}" class="{{ request()->routeIs('tickets.create') ? 'active' : '' }}"><span class="ico">+</span> Buat Tiket</a>
            <a href="{{ route('tickets.report') }}" class="{{ request()->routeIs('tickets.report') ? 'active' : '' }}"><span class="ico">◔</span> Laporan</a>
            <a href="{{ route('tickets.trash') }}" class="{{ request()->routeIs('tickets.trash') ? 'active' : '' }}"><span class="ico">♻</span> Tempat Sampah</a>
        </nav>

        <div class="side-card">
            <span class="dot"></span>
            <b>Pusat Layanan TI</b>
            <span>Senin-Jumat · 08.00-16.00</span>
        </div>
    
        <form method="POST" action="{{ route('logout') }}" class="side-logout">
    @csrf
    <button type="submit">⏻ Keluar</button>
</form>

    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <span class="eyebrow">Sistem Layanan TI</span>
                <h2>@yield('heading', 'Helpdesk')</h2>
            </div>
            <div class="top-right">
                <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">+ Tiket Baru</a>
                <div class="user">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <b>{{ auth()->user()->name }}</b>
                        <small>Petugas Helpdesk</small>
                    </div>
                    
                </div>
            </div>
        </header>

        <main class="content">
            @if (session('pesan'))
                <div class="flash">{{ session('pesan') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
