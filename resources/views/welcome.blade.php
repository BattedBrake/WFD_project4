<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediReserv - Reservasi Dokter Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">
    <header class="relative z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-sky-500 text-white shadow-sm">
                    <i class="fa-solid fa-stethoscope text-lg"></i>
                </span>
                <div>
                    <p class="text-lg font-extrabold leading-tight">MediReserv</p>
                    <p class="text-xs font-medium text-slate-400">Reservasi Dokter</p>
                </div>
            </a>

            <nav class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-sky-600">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="hidden rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-sky-600 sm:inline-flex">
                        Daftar
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="relative min-h-[calc(100vh-80px)] overflow-hidden bg-cover bg-center"
            style="background-image: url('{{ asset('images/welcomebg.jpg') }}')">
            <div class="absolute inset-0 bg-slate-50/88"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/10 to-white/30"></div>

            <div class="relative z-10 mx-auto grid min-h-[calc(100vh-80px)] max-w-7xl items-center gap-10 px-5 py-12 sm:px-8 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Sistem reservasi dokter untuk pasien, dokter, dan admin.
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg">
                        Cari dokter, pilih jadwal praktik, dan pantau reservasi dalam satu sistem yang rapi untuk klinik dan pasien.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600">
                                Buka Dashboard
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600">
                                Masuk Sekarang
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-100">
                                Daftar Pasien
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-400">Ringkasan</p>
                                <h2 class="text-xl font-bold">Aktivitas Klinik</h2>
                            </div>
                            <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-bold text-emerald-300">Online</span>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-white/10 p-4">
                                <i class="fa-solid fa-user-doctor text-sky-300"></i>
                                <p class="mt-4 text-2xl font-black">Dokter</p>
                                <p class="text-sm text-slate-400">Profil & spesialisasi</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-4">
                                <i class="fa-solid fa-calendar-days text-amber-300"></i>
                                <p class="mt-4 text-2xl font-black">Jadwal</p>
                                <p class="text-sm text-slate-400">Waktu praktik</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-4">
                                <i class="fa-solid fa-clipboard-list text-rose-300"></i>
                                <p class="mt-4 text-2xl font-black">Reservasi</p>
                                <p class="text-sm text-slate-400">Status kunjungan</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-4">
                                <i class="fa-solid fa-users text-violet-300"></i>
                                <p class="mt-4 text-2xl font-black">User</p>
                                <p class="text-sm text-slate-400">Data pengguna</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
