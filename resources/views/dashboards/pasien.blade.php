<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pasien</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="mx-auto max-w-5xl px-4 py-8">
        <div class="mb-6 flex flex-col gap-3 rounded-2xl border bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Dashboard Pasien</h1>
                <p class="text-sm text-slate-600">Cari jadwal dokter, buat reservasi, dan pantau status booking Anda.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reservations.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white">Buat Reservasi</a>
                <a href="{{ route('reservations.index') }}" class="rounded-lg border px-4 py-2">Lihat Reservasi</a>
            </div>
        </div>

        <div class="rounded-2xl border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg border px-4 py-2">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
