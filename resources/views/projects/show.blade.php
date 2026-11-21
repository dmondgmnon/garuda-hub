@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div class="card">
    <div style="margin-bottom: 10px;">
        <a href="{{ route('home') }}" class="muted">&larr; Kembali ke daftar proyek</a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
        <div>
            <div style="margin-bottom: 6px;">
                <span class="muted" style="font-weight: 600;">[{{ $project->category }}]</span>
                <span class="badge {{ $project->isOpen() ? 'open' : 'closed' }}">
                    {{ $project->isOpen() ? 'terbuka' : 'ditutup' }}
                </span>
            </div>
            <h1 style="margin-bottom: 6px;">{{ $project->title }}</h1>
            <div class="muted">
                Dipasang oleh: <strong>{{ $project->client->name }}</strong> &bull; Batas: {{ $project->deadline->format('d/m/Y') }}
            </div>
        </div>

        <div style="background: #f0f2f5; padding: 12px 16px; border-radius: 6px; text-align: right;">
            <div class="muted" style="font-size: 0.8rem;">Budget:</div>
            <div style="font-size: 1.15rem; font-weight: bold; color: #7a1020;">
                Rp {{ number_format($project->budget_min, 0, ',', '.') }} - {{ number_format($project->budget_max, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #e4e7eb; margin: 16px 0;">

    <h2>Deskripsi Proyek</h2>
    <div style="white-space: pre-line; line-height: 1.6;">
        {{ $project->description }}
    </div>
</div>

@auth
    @if(auth()->user()->role === 'developer')
        <div class="card">
            <h2>Ajukan Penawaran</h2>

            @if(!$project->isOpen())
                <p class="muted">Lelang sudah ditutup; penawaran tidak dapat diajukan.</p>
            @elseif($userBid)
                <div class="flash ok" style="margin-bottom: 0;">
                    Anda sudah mengajukan penawaran sebesar <strong>Rp {{ number_format($userBid->price, 0, ',', '.') }}</strong> dengan durasi <strong>{{ $userBid->duration_days }} hari</strong>.
                    @if($userBid->is_winner)
                        <br><strong style="color: #b7791f;">Penawaran Anda dipilih sebagai pemenang lelang ini!</strong>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('bids.store', $project) }}">
                    @csrf

                    <div class="row">
                        <div>
                            <label for="price">Harga Penawaran (Rp)</label>
                            <input type="number" id="price" name="price" min="10000" placeholder="Contoh: 2500000" required>
                        </div>
                        <div>
                            <label for="duration_days">Durasi (Hari)</label>
                            <input type="number" id="duration_days" name="duration_days" min="1" max="365" placeholder="Contoh: 14" required>
                        </div>
                    </div>

                    <label for="proposal">Proposal Penawaran</label>
                    <textarea id="proposal" name="proposal" placeholder="Jelaskan rencana pengerjaan dan keahlian Anda..." required></textarea>

                    <div style="margin-top: 15px;">
                        <button type="submit">Kirim Penawaran</button>
                    </div>
                </form>
            @endif
        </div>
    @endif
@else
    <div class="card" style="text-align: center;">
        <span class="muted">Ingin mengajukan penawaran pada proyek ini? Silakan <a href="{{ route('login') }}">Masuk</a> sebagai Developer.</span>
    </div>
@endauth

<div class="card">
    <h2>Daftar Penawaran ({{ $project->bids->count() }})</h2>

    @if($project->bids->isEmpty())
        <p class="muted">Belum ada penawaran pada proyek ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Developer</th>
                    <th>Harga</th>
                    <th>Durasi</th>
                    <th>Proposal</th>
                    <th>Status</th>
                    @if(auth()->check() && auth()->id() === $project->client_id && $project->isOpen())
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($project->bids as $bid)
                    <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                        <td>
                            <strong>{{ $bid->developer->name }}</strong>
                            <div class="muted" style="font-size: 0.8rem;">{{ $bid->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td><strong>Rp {{ number_format($bid->price, 0, ',', '.') }}</strong></td>
                        <td>{{ $bid->duration_days }} hari</td>
                        <td style="max-width: 320px; white-space: pre-line;">{{ $bid->proposal }}</td>
                        <td>
                            @if($bid->is_winner)
                                <span class="badge win">Pemenang</span>
                            @else
                                <span class="muted">-</span>
                            @endif
                        </td>
                        @if(auth()->check() && auth()->id() === $project->client_id && $project->isOpen())
                            <td>
                                <form method="POST" action="{{ route('bids.pick', [$project, $bid]) }}" onsubmit="return confirm('Pilih developer ini sebagai pemenang lelang? Proyek akan langsung ditutup.');">
                                    @csrf
                                    <button type="submit" class="sm">Pilih Pemenang</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
