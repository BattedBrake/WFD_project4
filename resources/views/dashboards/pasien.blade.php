<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pasien</title>
</head>
<body>
    <h1>Dashboard Pasien</h1>
    <p>Area untuk pasien mencari jadwal dokter dan membuat reservasi.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
