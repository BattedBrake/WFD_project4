<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien - MediReserv</title>
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
                    <a href="{{ route('pasien.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium transition shadow-md shadow-blue-600/20">
                        <i class="fa-solid fa-house w-5"></i> Dashboard
                    </a>
    
                    <a href="{{ route('reservations.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
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
                    <h1 class="text-xl font-bold text-slate-800">Dashboard Pasien</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    
                    <div class="h-10 w-10 overflow-hidden rounded-full border border-slate-200">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-full w-full object-cover" alt="Foto Profil">
                        @else
                            <div class="h-full w-full bg-blue-100 text-blue-600 font-bold grid place-items-center uppercase text-sm">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <main class="p-8 max-w-7xl w-full mx-auto space-y-8">
                <div class="bg-gradient-to-r from-blue-600 to-sky-500 rounded-2xl p-6 md:p-8 text-white shadow-lg shadow-blue-600/10">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-blue-100 max-w-xl">
                        Kesehatan Anda adalah prioritas kami. Di sini Anda dapat menjadwalkan konsultasi medis baru atau melihat status reservasi aktif Anda.
                    </p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-500 grid place-items-center text-xl">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Reservasi Menunggu</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $jumlahMenunggu }}</p>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 grid place-items-center text-xl">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Reservasi Disetujui</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $jumlahDisetujui }}</p>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5 shadow-sm sm:col-span-2 lg:col-span-1">
                        <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 grid place-items-center text-xl">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Riwayat Periksa</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $totalRiwayat }}</p>
                        </div>
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
