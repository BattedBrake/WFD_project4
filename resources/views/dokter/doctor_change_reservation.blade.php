<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Pasien - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased">
    <input type="checkbox" id="sidebar-toggle" class="peer hidden">
    <label for="sidebar-toggle" class="fixed inset-0 z-30 hidden bg-slate-900/50 peer-checked:block md:hidden"></label>

    <div class="flex h-screen overflow-hidden">
        <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col justify-between bg-slate-900 text-white transition-transform duration-200 peer-checked:translate-x-0 md:static md:translate-x-0">
            <div class="p-5">
                <div class="mb-8 flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-500 text-white">
                        <i class="fa-solid fa-stethoscope"></i>
                    </span>
                    <span class="text-lg font-bold tracking-wider">MediReserv</span>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('dokter.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-slate-400 transition hover:bg-slate-800 hover:text-white">
                        <i class="fa-solid fa-house w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('dokter.schedules.create') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-slate-400 transition hover:bg-slate-800 hover:text-white">
                        <i class="fa-solid fa-calendar-plus w-5"></i> Input Jam Tersedia
                    </a>
                    <a href="{{ route('dokter.schedules') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-slate-400 transition hover:bg-slate-800 hover:text-white">
                        <i class="fa-solid fa-calendar-days w-5"></i> Jadwal Saya
                    </a>
                    <a href="{{ route('dokter.reservations') }}" class="flex items-center gap-3 rounded-xl bg-blue-600 px-4 py-3 font-medium text-white shadow-md shadow-blue-600/20 transition">
                        <i class="fa-solid fa-user-injured w-5"></i> Reservasi Pasien
                    </a>
                </nav>
            </div>

            <div class="border-t border-slate-800 p-5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 font-medium text-red-400 transition hover:bg-red-500/10 hover:text-red-300">
                        <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-grow flex-col overflow-y-auto">
            <header class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-4 sm:px-8">
                <div class="flex items-center gap-3">
                    <label for="sidebar-toggle" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-600 md:hidden">
                        <i class="fa-solid fa-bars"></i>
                    </label>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Reservasi Pasien</h1>
                        <p class="text-xs text-slate-400">Ubah status konsultasi sesuai progres pemeriksaan.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">Dr. {{ Auth::user()->name }}</p>
                        <p class="text-xs capitalize text-slate-400">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-full border border-emerald-200 bg-emerald-100 font-bold uppercase text-emerald-600">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
            </header>

            <main class="mx-auto w-full max-w-7xl space-y-6 p-8">
                @if(session('success'))
                    <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-lg font-bold text-slate-800">Daftar Reservasi Masuk</h2>
                        <p class="mt-1 text-sm text-slate-500">Hanya reservasi yang masuk ke jadwal praktik dokter ini yang ditampilkan.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-slate-700">
                            <thead class="border-b border-slate-200 bg-slate-50 text-left font-semibold text-slate-600">
                                <tr>
                                    <th class="px-6 py-4">Pasien</th>
                                    <th class="px-6 py-4">Jadwal</th>
                                    <th class="px-6 py-4">Keluhan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Ubah Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($reservations as $reservation)
                                    <tr class="transition hover:bg-slate-50/70">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-slate-900">{{ $reservation->user->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $reservation->user->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p>{{ $reservation->schedule->date }}</p>
                                            <p class="text-xs text-slate-400">{{ substr($reservation->schedule->start_time, 0, 5) }} - {{ substr($reservation->schedule->end_time, 0, 5) }}</p>
                                        </td>
                                        <td class="max-w-xs px-6 py-4 text-slate-500">
                                            {{ \Illuminate\Support\Str::limit($reservation->complaint ?? '-', 70) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold uppercase tracking-wider
                                                {{ $reservation->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ $reservation->status === 'hold' ? 'bg-blue-50 text-blue-700' : '' }}
                                                {{ $reservation->status === 'done' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                {{ $reservation->status === 'cancelled' ? 'bg-red-50 text-red-700' : '' }}">
                                                {{ $reservation->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('reservations.status', $reservation) }}" class="flex items-center justify-end gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="rounded-lg border border-slate-200 px-3 py-2 text-xs outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                                    <option value="pending" @selected($reservation->status === 'pending')>Pending</option>
                                                    <option value="hold" @selected($reservation->status === 'hold')>Hold</option>
                                                    <option value="done" @selected($reservation->status === 'done')>Done</option>
                                                    <option value="cancelled" @selected($reservation->status === 'cancelled')>Cancelled</option>
                                                </select>
                                                <button class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-700">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                            <i class="fa-solid fa-calendar-xmark mb-2 block text-2xl text-slate-300"></i>
                                            Belum ada reservasi pasien.
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

    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('aside');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('change', () => {
                sidebar.classList.toggle('-translate-x-full', !sidebarToggle.checked);
            });
        }
    </script>
</body>
</html>
