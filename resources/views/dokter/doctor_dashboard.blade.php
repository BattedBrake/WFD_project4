<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - MediReserv</title>
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
                    <a href="{{ route('dokter.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium transition shadow-md shadow-blue-600/20">
                        <i class="fa-solid fa-house w-5"></i> Dashboard
                    </a>

                    <a href="{{ route('dokter.schedules.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                        <i class="fa-solid fa-calendar-plus w-5"></i> Input Jam Tersedia
                    </a>

                    <a href="{{ route('dokter.schedules') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                        <i class="fa-solid fa-calendar-days w-5"></i> Jadwal Saya
                    </a>
                    
                    <a href="{{ route('dokter.reservations') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                        <i class="fa-solid fa-user-injured w-5"></i> Reservasi Pasien
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
                    <h1 class="text-xl font-bold text-slate-800">Dashboard Dokter</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">Dr. {{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 font-bold grid place-items-center uppercase border border-emerald-200">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
            </header>

            <main class="p-8 max-w-7xl w-full mx-auto space-y-8">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 md:p-8 text-white shadow-lg shadow-slate-950/10">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang, Dr. {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-slate-300 max-w-xl">
                        Tetap pantau jadwal praktik Anda dan kelola permohonan konsultasi pasien hari ini dengan efisien.
                    </p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-500 grid place-items-center text-xl">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Reservasi Pending</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $jumlahPending }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 grid place-items-center text-xl">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Jadwal Praktik</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $totalJadwal }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5 shadow-sm sm:col-span-2 lg:col-span-1">
                        <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 grid place-items-center text-xl">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Pasien Selesai Diperiksa</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $pasienSelesai }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Aksi Cepat Manajemen Medis</h3>
                    <p class="text-sm text-slate-500 mb-4">Gunakan pintasan di bawah ini untuk mengelola operasional Anda langsung dari dashboard:</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dokter.reservations') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition flex items-center gap-2">
                            <i class="fa-solid fa-eye"></i> Lihat Antrean Pasien
                        </a>
                        <a href="{{ route('dokter.schedules.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition flex items-center gap-2">
                            <i class="fa-solid fa-calendar-plus"></i> Input Jam Tersedia
                        </a>
                        <a href="{{ route('dokter.schedules') }}" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days"></i> Lihat Jadwal
                        </a>
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
