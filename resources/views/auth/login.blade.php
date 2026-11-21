@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="card" style="max-width: 420px; margin: 30px auto;">
    <h1>Masuk</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div style="margin: 15px 0;">
            <label style="display: inline; font-weight: normal;">
                <input type="checkbox" name="remember" style="width: auto;"> Ingat saya di komputer ini
            </label>
        </div>

        <button type="submit" style="width: 100%;">Masuk</button>
    </form>

    <p class="muted" style="text-align: center; margin-top: 15px;">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
    </p>
</div>
@endsection
