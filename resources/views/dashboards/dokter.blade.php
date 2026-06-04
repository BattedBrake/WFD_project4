<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Dokter</title>
</head>
<body>
    <h1>Dashboard Dokter</h1>
    <p>Area untuk dokter melihat jadwal dan reservasi pasien.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
