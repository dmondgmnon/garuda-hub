@extends('layouts.app')

@section('title', 'Dasbor Klien')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div>
            <h1 style="margin-bottom: 4px;">Proyek Saya</h1>
            <span class="muted">Kelola proyek yang telah Anda pasang.</span>
        </div>
        <a href="{{ route('projects.create') }}" class="btn sm">+ Pasang Proyek Baru</a>
    </div>

    @if($projects->isEmpty())
        <p class="muted" style="text-align: center; padding: 20px 0;">
            Anda belum pernah memasang proyek. <a href="{{ route('projects.create') }}">Pasang proyek sekarang</a>.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Judul Proyek</th>
                    <th>Kategori</th>
                    <th>Budget</th>
                    <th>Batas Lelang</th>
                    <th>Penawaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td>
                            <strong><a href="{{ route('projects.show', $p) }}">{{ $p->title }}</a></strong>
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
                        <td>
                            <a href="{{ route('projects.show', $p) }}" class="btn sm sec">Lihat Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}
    @endif
</div>
@endsection
