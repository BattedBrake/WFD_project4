<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Reservasi - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased">
    <input type="checkbox" id="sidebar-toggle" class="peer hidden">
    <label for="sidebar-toggle" class="fixed inset-0 z-30 hidden bg-slate-900/50 peer-checked:block md:hidden"></label>

    <div class="flex h-screen overflow-hidden">
        <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col justify-between bg-slate-900 text-white transition-transform duration-200 peer-checked:translate-x-0 md:static md:translate-x-0">
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
                    <a href="{{ route('reservations.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium transition shadow-md shadow-blue-600/20">
                        <i class="fa-solid fa-calendar-plus w-5"></i> Buat Reservasi
                    </a>
                    <a href="{{ route('reservations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
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

        <div class="flex min-w-0 flex-grow flex-col overflow-y-auto">
            <header class="bg-white border-b border-slate-200 px-4 py-4 flex justify-between items-center sticky top-0 z-10 sm:px-8">
                <div class="flex items-center gap-3">
                    <label for="sidebar-toggle" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-600 md:hidden">
                        <i class="fa-solid fa-bars"></i>
                    </label>
                    <h1 class="text-xl font-bold text-slate-800">Formulir Reservasi</h1>
                </div>
                <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
            </header>

            <main class="p-8 max-w-3xl w-full mx-auto">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-800 mb-2">Pilih Jadwal Dokter</h2>
                    <p class="text-sm text-slate-500 mb-6">Silakan pilih jadwal praktik dokter yang tersedia dan kirim keluhan Anda.</p>

                    <form method="POST" action="{{ route('reservations.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="schedule_id" class="block text-sm font-semibold text-slate-700 mb-2">Jadwal Dokter</label>
                            <div class="relative">
                                <select name="schedule_id" id="schedule_id" required class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-slate-700">
                                    <option value="">Pilih jadwal</option>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}" @selected(old('schedule_id') == $schedule->id)>
                                            Dr. {{ $schedule->doctor->user->name ?? 'Dokter' }} ({{ $schedule->doctor->specialization }}) — {{ $schedule->date }} [{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('schedule_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="complaint" class="block text-sm font-semibold text-slate-700 mb-2">Keluhan</label>
                            <textarea name="complaint" id="complaint" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-slate-700 resize-none" placeholder="Ceritakan keluhan Anda...">{{ old('complaint') }}</textarea>
                            @error('complaint')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('reservations.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</a>
                            <button class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-600/10 transition">Submit Reservasi</button>
                        </div>
                    </form>
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
