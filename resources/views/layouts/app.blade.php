<!DOCTYPE html>
{{-- NIM: 252410102049 - Gandhi MM --}}
{{-- Layout utama Garuda-Hub: Output encoding via Blade {{ }} untuk mencegah Stored/Reflected XSS --}}
{{-- Semua form menggunakan @csrf untuk perlindungan CSRF --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - Garuda-Hub</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background: #f4f6f8; color: #1f2933; line-height: 1.5; }
        a { color: #0b5cad; text-decoration: none; }
        a:hover { text-decoration: underline; }
        header { background: #7a1020; color: #fff; }
        header .in { max-width: 1000px; margin: auto; padding: 12px 16px; display: flex; flex-wrap: wrap; align-items: center; gap: 14px; }
        header a { color: #fff; }
        header .brand { font-weight: 700; font-size: 1.3rem; margin-right: auto; }
        main { max-width: 1000px; margin: 20px auto; padding: 0 16px; min-height: 70vh; }
        .card { background: #fff; border-radius: 8px; padding: 18px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        h1 { font-size: 1.5rem; margin: 0 0 14px; }
        h2 { font-size: 1.15rem; margin: 0 0 10px; }
        label { display: block; margin: 10px 0 4px; font-weight: 600; font-size: 0.9rem; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #c5ccd3; border-radius: 6px; font: inherit; }
        textarea { min-height: 110px; }
        .btn, button { display: inline-block; background: #7a1020; color: #fff; border: 0; border-radius: 6px; padding: 8px 16px; font: inherit; cursor: pointer; text-decoration: none; }
        .btn.sec, button.sec { background: #52606d; }
        .btn.danger, button.danger { background: #b42318; }
        .btn.sm, button.sm { padding: 4px 10px; font-size: .85rem; }
        .btn:hover { text-decoration: none; opacity: .9; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #e4e7eb; vertical-align: top; font-size: 0.9rem; }
        th { background: #f0f2f5; font-weight: 600; }
        .badge { display: inline-block; padding: 2px 9px; border-radius: 12px; font-size: .8rem; color: #fff; }
        .badge.open { background: #1a7f37; }
        .badge.closed { background: #6b7280; }
        .badge.win { background: #b7791f; font-weight: 600; }
        .badge.klien { background: #3730a3; }
        .badge.dev { background: #166534; }
        .badge.admin { background: #92400e; }
        .flash { padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 0.9rem; }
        .flash.ok { background: #d9f2e0; color: #166534; border: 1px solid #bbf7d0; }
        .flash.err { background: #fde2e0; color: #991b1b; border: 1px solid #fecaca; }
        .muted { color: #6b7280; font-size: .9rem; }
        .row { display: flex; gap: 12px; flex-wrap: wrap; }
        .row > * { flex: 1; min-width: 140px; }
        form.inline { display: inline; }
        form.inline select { width: auto; padding: 4px 8px; font-size: 0.85rem; }
        .winner { background: #fff8e1; }
        .search { display: flex; gap: 8px; margin-bottom: 16px; }
        .search input { flex: 1; }
        .search button { flex: none; }
        footer { text-align: center; color: #6b7280; font-size: .85rem; margin: 30px 0; padding-top: 15px; border-top: 1px solid #e4e7eb; }
        .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin-top: 15px; }
        .pagination li a, .pagination li span { padding: 4px 10px; border: 1px solid #c5ccd3; border-radius: 4px; font-size: 0.85rem; text-decoration: none; color: #1f2933; }
        .pagination li.active span { background: #7a1020; color: #fff; border-color: #7a1020; }
    </style>
</head>
<body>
    <header>
        <div class="in">
            <a class="brand" href="{{ route('home') }}">Garuda-Hub</a>
            <a href="{{ route('home') }}">Proyek</a>
            @auth
                @if(auth()->user()->role === 'klien')
                    <a href="{{ route('projects.create') }}">Pasang Proyek</a>
                @endif
                @if(auth()->user()->role !== 'admin')
                    <a href="{{ route('dashboard') }}">Dasbor</a>
                @endif
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <span class="muted" style="color: #ffe4e6;">
                    {{ auth()->user()->name }} ({{ auth()->user()->role }})
                </span>
                <form class="inline" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="sm sec" type="submit">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </header>

    <main>
        @if(session('success'))
            <div class="flash ok">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="flash err">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="flash err">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        Garuda-Hub - Platform Lelang Proyek IT Mahasiswa. Berbasis Laravel MVC & Eloquent ORM.
    </footer>
</body>
</html>
