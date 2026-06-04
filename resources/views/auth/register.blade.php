<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Reservasi Dokter</title>
</head>
<body>
    <h1>Register Pasien</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label>
            Nama
            <input type="text" name="name" value="{{ old('name') }}" required autofocus>
        </label>
        <label>
            Email
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <label>
            Konfirmasi Password
            <input type="password" name="password_confirmation" required>
        </label>
        <button type="submit">Daftar</button>
    </form>

    <a href="{{ route('login') }}">Sudah punya akun?</a>
</body>
</html>
