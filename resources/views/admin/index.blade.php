@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<div class="card">
    <h1>Panel Admin</h1>
    <span class="muted">Pengelolaan pengguna dan proyek dalam sistem.</span>
</div>

<div class="card">
    <h2>Kelola Pengguna ({{ $users->total() }})</h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Peran</th>
                <th>Ubah Peran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
                <tr>
                    <td>
                        <strong>{{ $u->name }}</strong>
                        @if($u->id === auth()->id())
                            <span class="muted">(akun Anda)</span>
                        @endif
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge {{ $u->role === 'klien' ? 'klien' : ($u->role === 'developer' ? 'dev' : 'admin') }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td>
                        @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline">
                                @csrf
                                <select name="role">
                                    <option value="klien" {{ $u->role === 'klien' ? 'selected' : '' }}>klien</option>
                                    <option value="developer" {{ $u->role === 'developer' ? 'selected' : '' }}>developer</option>
                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>admin</option>
                                </select>
                                <button type="submit" class="sm sec">Ubah</button>
                            </form>
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.delete', $u) }}" class="inline" onsubmit="return confirm('Hapus pengguna ini beserta semua data proyek dan penawarannya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sm danger">Hapus</button>
                            </form>
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>

<div class="card">
    <h2>Kelola Proyek ({{ $projects->total() }})</h2>

    <table>
        <thead>
            <tr>
                <th>Judul Proyek</th>
                <th>Klien</th>
                <th>Budget Max</th>
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
                    <td>{{ $p->client->name }}</td>
                    <td>Rp {{ number_format($p->budget_max, 0, ',', '.') }}</td>
                    <td>{{ $p->bids_count }} penawaran</td>
                    <td>
                        <span class="badge {{ $p->isOpen() ? 'open' : 'closed' }}">
                            {{ $p->isOpen() ? 'terbuka' : 'ditutup' }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.projects.delete', $p) }}" class="inline" onsubmit="return confirm('Hapus proyek ini beserta seluruh penawaran yang ada?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="sm danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $projects->links() }}
</div>
@endsection
