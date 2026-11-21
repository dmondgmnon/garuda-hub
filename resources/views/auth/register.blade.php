@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="card" style="max-width: 460px; margin: 30px auto;">
    <h1>Daftar Akun Baru</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>

        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>

        <label for="role">Peran</label>
        <select id="role" name="role" required>
            <option value="">-- Pilih Peran --</option>
            <option value="klien" {{ old('role') == 'klien' ? 'selected' : '' }}>Klien (Pembuat Proyek)</option>
            <option value="developer" {{ old('role') == 'developer' ? 'selected' : '' }}>Developer (Pengaju Penawaran)</option>
        </select>

        <label for="password">Password (minimal 6 karakter)</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Ulangi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <div style="margin-top: 20px;">
            <button type="submit" style="width: 100%;">Daftar</button>
        </div>
    </form>

    <p class="muted" style="text-align: center; margin-top: 15px;">
        Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a>
    </p>
</div>
@endsection
