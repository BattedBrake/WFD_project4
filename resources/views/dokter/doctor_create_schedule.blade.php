<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Jadwal Dokter - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/50 md:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col justify-between bg-slate-900 text-white transition-transform duration-200 md:static md:translate-x-0">
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
                    <a href="{{ route('dokter.schedules.create') }}" class="flex items-center gap-3 rounded-xl bg-blue-600 px-4 py-3 font-medium text-white shadow-md shadow-blue-600/20 transition">
                        <i class="fa-solid fa-calendar-plus w-5"></i> Input Jam Tersedia
                    </a>
                    <a href="{{ route('dokter.schedules') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-slate-400 transition hover:bg-slate-800 hover:text-white">
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
                    <button type="button" data-sidebar-open class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-600 md:hidden">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Input Jam Tersedia</h1>
                        <p class="text-xs text-slate-400">Tambahkan jadwal praktik yang bisa dipilih pasien.</p>
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

            <main class="mx-auto w-full max-w-3xl p-4 sm:p-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-slate-800">Form Jadwal Praktik</h2>
                        <p class="mt-1 text-sm text-slate-500">Isi hari praktik, jam mulai, jam selesai, dan kuota pasien untuk jadwal ini.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dokter.schedules.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="day_of_week" class="mb-2 block text-sm font-semibold text-slate-700">Hari Praktik</label>
                            <select name="day_of_week" id="day_of_week" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                <option value="">Pilih hari</option>
                                @foreach(['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('day_of_week') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="start_time" class="mb-2 block text-sm font-semibold text-slate-700">Jam Mulai</label>
                                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                            </div>
                            <div>
                                <label for="end_time" class="mb-2 block text-sm font-semibold text-slate-700">Jam Selesai</label>
                                <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                            </div>
                        </div>

                        <div>
                            <label for="quota" class="mb-2 block text-sm font-semibold text-slate-700">Kuota Pasien</label>
                            <input type="number" name="quota" id="quota" value="{{ old('quota', 1) }}" min="1" max="100" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <a href="{{ route('dokter.schedules') }}" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</a>
                            <button class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/10 transition hover:bg-blue-700">
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openButtons = document.querySelectorAll('[data-sidebar-open]');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        openButtons.forEach((button) => button.addEventListener('click', openSidebar));
        overlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
