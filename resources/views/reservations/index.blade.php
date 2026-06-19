<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Reservasi</h1>
                <p class="text-sm text-slate-600">Pantau status booking dan keluhan pasien.</p>
            </div>
            <div class="flex gap-3">
                @if(!$isDoctor)
                    <a href="{{ route('reservations.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white">Buat Reservasi</a>
                @endif
                <a href="{{ route('dashboard') }}" class="rounded-lg border px-4 py-2">Kembali</a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border bg-white p-4 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-left">
                        <tr>
                            <th class="px-3 py-2">Pasien</th>
                            <th class="px-3 py-2">Dokter</th>
                            <th class="px-3 py-2">Jadwal</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr class="border-t">
                                <td class="px-3 py-3">{{ $reservation->user->name }}</td>
                                <td class="px-3 py-3">{{ $reservation->schedule->doctor->user->name ?? '-' }}</td>
                                <td class="px-3 py-3">{{ \Illuminate\Support\Str::limit($reservation->complaint ?? '-', 60) }}</td>
                                <td class="px-3 py-3 capitalize">{{ $reservation->status }}</td>
                                <td class="px-3 py-3">
                                    @if(!$isDoctor && $reservation->canBeCancelled())
                                        <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">
                                            @csrf
                                            <button class="rounded bg-amber-600 px-3 py-2 text-white">Cancel</button>
                                        </form>
                                    @elseif($isDoctor)
                                        <form method="POST" action="{{ route('reservations.status', $reservation) }}" class="flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="rounded border px-2 py-1">
                                                <option value="pending" @selected($reservation->status === 'pending')>Pending</option>
                                                <option value="hold" @selected($reservation->status === 'hold')>Hold</option>
                                                <option value="done" @selected($reservation->status === 'done')>Done</option>
                                                <option value="cancelled" @selected($reservation->status === 'cancelled')>Cancelled</option>
                                            </select>
                                            <button class="rounded bg-green-600 px-3 py-2 text-white">Simpan</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada reservasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
