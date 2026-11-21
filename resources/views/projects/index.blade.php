@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
        <div>
            <h1 style="margin-bottom: 4px;">Daftar Proyek IT</h1>
            <span class="muted">Cari dan temukan proyek lelang yang sedang dibuka.</span>
        </div>
        @auth
            @if(auth()->user()->role === 'klien')
                <a href="{{ route('projects.create') }}" class="btn sm">+ Pasang Proyek</a>
            @endif
        @endauth
    </div>

    <form method="GET" action="{{ route('home') }}" class="search">
        <input type="text" name="q" placeholder="Cari berdasarkan judul atau kategori..." value="{{ $search }}">
        <button type="submit">Cari</button>
        @if($search)
            <a href="{{ route('home') }}" class="btn sec">Reset</a>
        @endif
    </form>

    @if($projects->isEmpty())
        <p class="muted" style="text-align: center; padding: 30px 0;">Tidak ada proyek yang ditemukan.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Judul Proyek</th>
                    <th>Kategori</th>
                    <th>Rentang Budget</th>
                    <th>Batas Lelang</th>
                    <th>Penawaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td>
                            <strong><a href="{{ route('projects.show', $p) }}">{{ $p->title }}</a></strong>
                            <div class="muted" style="font-size: 0.8rem;">Oleh: {{ $p->client->name }}</div>
                        </td>
                        <td>{{ $p->category }}</td>
                        <td>Rp {{ number_format($p->budget_min, 0, ',', '.') }} - {{ number_format($p->budget_max, 0, ',', '.') }}</td>
                        <td>{{ $p->deadline->format('d/m/Y') }}</td>
                        <td>{{ $p->bids_count }} penawaran</td>
                        <td>
                            <span class="badge {{ $p->isOpen() ? 'open' : 'closed' }}">
                                {{ $p->isOpen() ? 'terbuka' : 'ditutup' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}
    @endif
</div>
@endsection
