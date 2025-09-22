@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 480px; margin: 24px auto;">
        <div class="title" style="font-weight:700; color:#6D2323;">Masuk</div>

        @if ($errors->any())
            <div style="color:#b91c1c; font-size: 13px; margin-bottom: 10px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="field" style="margin-bottom:12px;">
                <label for="username">Nama</label>
                <input id="username" name="username" type="text" placeholder="contoh: Admin Satu" required>
            </div>
            <div class="field" style="margin-bottom:16px;">
                <label for="role">Masuk sebagai</label>
                <select id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" style="background:#6D2323;">Login</button>
        </form>
    </div>
@endsection


