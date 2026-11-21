@extends('layouts.app')

@section('title', 'Pasang Proyek')

@section('content')
<div class="card" style="max-width: 650px; margin: 20px auto;">
    <h1>Pasang Proyek Baru</h1>
    <p class="muted" style="margin-top: -8px; margin-bottom: 16px;">
        Masukkan rincian proyek IT yang ingin Anda lelangkan ke developer.
    </p>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf

        <label for="title">Judul Proyek</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Pembuatan Website E-Commerce Toko Pakaian" required>

        <label for="category">Kategori</label>
        <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="Contoh: Web, Mobile, Desain, Data" required>

        <div class="row">
            <div>
                <label for="budget_min">Budget Minimum (Rp)</label>
                <input type="number" id="budget_min" name="budget_min" value="{{ old('budget_min') }}" min="10000" placeholder="Contoh: 1000000" required>
            </div>
            <div>
                <label for="budget_max">Budget Maksimum (Rp)</label>
                <input type="number" id="budget_max" name="budget_max" value="{{ old('budget_max') }}" min="10000" placeholder="Contoh: 5000000" required>
            </div>
        </div>

        <label for="deadline">Batas Lelang</label>
        <input type="date" id="deadline" name="deadline" value="{{ old('deadline', date('Y-m-d', strtotime('+14 days'))) }}" min="{{ date('Y-m-d') }}" required>

        <label for="description">Deskripsi Proyek</label>
        <textarea id="description" name="description" placeholder="Jelaskan kebutuhan fungsional dan teknis secara jelas..." required>{{ old('description') }}</textarea>

        <div style="margin-top: 20px; display: flex; gap: 8px;">
            <button type="submit">Pasang Proyek</button>
            <a href="{{ route('dashboard') }}" class="btn sec">Batal</a>
        </div>
    </form>
</div>
@endsection
