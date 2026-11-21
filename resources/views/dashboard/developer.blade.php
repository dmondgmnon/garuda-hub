@extends('layouts.app')

@section('title', 'Dasbor Developer')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div>
            <h1 style="margin-bottom: 4px;">Penawaran Saya</h1>
            <span class="muted">Daftar penawaran lelang yang telah Anda kirimkan.</span>
        </div>
        <a href="{{ route('home') }}" class="btn sm sec">Cari Proyek</a>
    </div>

    @if($bids->isEmpty())
        <p class="muted" style="text-align: center; padding: 20px 0;">
            Anda belum mengajukan penawaran. <a href="{{ route('home') }}">Lihat proyek yang terbuka</a>.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Proyek</th>
                    <th>Klien</th>
                    <th>Harga Penawaran</th>
                    <th>Durasi</th>
                    <th>Status Lelang</th>
                    <th>Hasil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $bid)
                    <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                        <td>
                            <strong><a href="{{ route('projects.show', $bid->project) }}">{{ $bid->project->title }}</a></strong>
                            <div class="muted" style="font-size: 0.8rem;">Dikirim: {{ $bid->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td>{{ $bid->project->client->name }}</td>
                        <td><strong>Rp {{ number_format($bid->price, 0, ',', '.') }}</strong></td>
                        <td>{{ $bid->duration_days }} hari</td>
                        <td>
                            <span class="badge {{ $bid->project->isOpen() ? 'open' : 'closed' }}">
                                {{ $bid->project->isOpen() ? 'terbuka' : 'ditutup' }}
                            </span>
                        </td>
                        <td>
                            @if($bid->is_winner)
                                <span class="badge win">Pemenang</span>
                            @elseif($bid->project->status === 'ditutup')
                                <span class="muted">Tidak terpilih</span>
                            @else
                                <span class="muted">Menunggu</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('projects.show', $bid->project) }}" class="btn sm sec">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $bids->links() }}
    @endif
</div>
@endsection
