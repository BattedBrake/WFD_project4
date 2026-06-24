<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dokter - MediReserv</title>
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
                    <a href="{{ route('dokter.schedules') }}" class="flex items-center gap-3 rounded-xl bg-blue-600 px-4 py-3 font-medium text-white shadow-md shadow-blue-600/20 transition">
                        <i class="fa-solid fa-calendar-days w-5"></i> Jadwal Saya
                    </a>
                    <a href="{{ route('dokter.reservations') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-slate-400 transition hover:bg-slate-800 hover:text-white">
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
                        <h1 class="text-xl font-bold text-slate-800">Jadwal Saya</h1>
                        <p class="text-xs text-slate-400">Pantau jadwal praktik dan sisa kuota reservasi pasien.</p>
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
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="flex items-center gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-blue-50 text-xl text-blue-500">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Jadwal</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $schedules->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-emerald-50 text-xl text-emerald-500">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Reservasi Aktif</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $schedules->sum('active_reservations_count') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:col-span-2 lg:col-span-1">
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-amber-50 text-xl text-amber-500">
                            <i class="fa-solid fa-chair"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Kuota</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $schedules->sum('quota') }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Daftar Jadwal Praktik</h2>
                                <p class="mt-1 text-sm text-slate-500">Jadwal yang ditambahkan dokter dan siap dipilih pasien.</p>
                            </div>
                            <a href="{{ route('dokter.schedules.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/10 transition hover:bg-blue-700">
                                <i class="fa-solid fa-plus text-xs"></i> Tambah Jadwal
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-slate-700">
                            <thead class="border-b border-slate-200 bg-slate-50 text-left font-semibold text-slate-600">
                                <tr>
                                    <th class="px-6 py-4">Hari</th>
                                    <th class="px-6 py-4">Jam Praktik</th>
                                    <th class="px-6 py-4">Kuota</th>
                                    <th class="px-6 py-4">Reservasi Aktif</th>
                                    <th class="px-6 py-4">Sisa Kuota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($schedules as $schedule)
                                    @php
                                        $remainingQuota = max($schedule->quota - $schedule->active_reservations_count, 0);
                                    @endphp
                                    <tr class="transition hover:bg-slate-50/70">
                                        <td class="px-6 py-4 font-semibold text-slate-900">
                                            {{ ucfirst($schedule->day_of_week ?? $schedule->date) }}
                                        </td>
                                        <td class="px-6 py-4">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                                        <td class="px-6 py-4">{{ $schedule->quota }}</td>
                                        <td class="px-6 py-4">{{ $schedule->active_reservations_count }}</td>
                                        <td class="px-6 py-4">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $remainingQuota > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                {{ $remainingQuota }} tersisa
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                            <i class="fa-solid fa-calendar-xmark mb-2 block text-2xl text-slate-300"></i>
                                            Belum ada jadwal praktik.
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
