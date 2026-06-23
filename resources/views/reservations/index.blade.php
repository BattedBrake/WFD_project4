<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Reservasi - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-8">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-500 text-white">
                        <i class="fa-solid fa-stethoscope"></i>
                    </span>
                    <span class="text-lg font-bold tracking-wider">MediReserv</span>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('pasien.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                        <i class="fa-solid fa-house w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('reservations.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                        <i class="fa-solid fa-calendar-plus w-5"></i> Buat Reservasi
                    </a>
                    <a href="{{ route('reservations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium transition shadow-md shadow-blue-600/20">
                        <i class="fa-solid fa-clock-rotate-left w-5"></i> Riwayat Konsultasi
                    </a>
                </nav>
            </div>

            <div class="p-5 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 font-medium transition">
                        <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-grow flex flex-col overflow-y-auto">
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Reservasi Konsultasi</h1>
                    <p class="text-xs text-slate-400">Pantau status booking dan keluhan pasien.</p>
                </div>
                <div class="flex gap-3">
                    @if(!$isDoctor)
                        <a href="{{ route('reservations.create') }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition shadow-md shadow-blue-600/10">Buat Reservasi</a>
                    @endif
                </div>
            </header>

            <main class="p-8 max-w-6xl w-full mx-auto space-y-6">
                @if(session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-slate-700">
                            <thead class="bg-slate-50 border-b border-slate-200 text-left font-semibold text-slate-600">
                                <tr>
                                    <th class="px-6 py-4">Pasien</th>
                                    <th class="px-6 py-4">Dokter</th>
                                    <th class="px-6 py-4">Jadwal Praktik</th>
                                    <th class="px-6 py-4">Keluhan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($reservations as $reservation)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4 font-medium text-slate-900">{{ $reservation->user->name }}</td>
                                        <td class="px-6 py-4">Dr. {{ $reservation->schedule->doctor->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            {{ $reservation->schedule->date }} 
                                            <span class="text-xs text-slate-400 block">{{ substr($reservation->schedule->start_time, 0, 5) }} - {{ substr($reservation->schedule->end_time, 0, 5) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">{{ \Illuminate\Support\Str::limit($reservation->complaint ?? '-', 40) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                                                {{ $reservation->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ $reservation->status === 'done' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                {{ $reservation->status === 'cancelled' ? 'bg-red-50 text-red-700' : '' }}
                                                {{ $reservation->status === 'hold' ? 'bg-blue-50 text-blue-700' : '' }}
                                            ">
                                                {{ $reservation->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if(!$isDoctor && $reservation->canBeCancelled())
                                                <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" class="inline-block">
                                                    @csrf
                                                    <button class="rounded-xl border border-red-200 text-red-600 px-3 py-1.5 text-xs font-semibold hover:bg-red-50 transition">Cancel</button>
                                                </form>
                                            @elseif($isDoctor)
                                                <form method="POST" action="{{ route('reservations.status', $reservation) }}" class="flex items-center justify-end gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="rounded-lg border border-slate-200 px-2 py-1 text-xs outline-none">
                                                        <option value="pending" @selected($reservation->status === 'pending')>Pending</option>
                                                        <option value="hold" @selected($reservation->status === 'hold')>Hold</option>
                                                        <option value="done" @selected($reservation->status === 'done')>Done</option>
                                                        <option value="cancelled" @selected($reservation->status === 'cancelled')>Cancelled</option>
                                                    </select>
                                                    <button class="rounded-lg bg-emerald-600 px-3 py-1 text-xs font-bold text-white hover:bg-emerald-700 transition">Simpan</button>
                                                </form>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                            <i class="fa-solid fa-calendar-xmark text-2xl block mb-2 text-slate-300"></i>
                                            Belum ada data reservasi konsultasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>