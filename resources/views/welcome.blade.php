<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediReserv - Reservasi Dokter Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }

        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
        .float-anim { animation: floatUp 5s ease-in-out infinite; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50%       { opacity: .4; }
        }
        .pulse-dot { animation: pulse-dot 1.8s ease-in-out infinite; }

        .hero-bg {
            background: radial-gradient(ellipse 80% 70% at 50% -10%, #bae6fd 0%, transparent 70%),
                        linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);
        }

        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased">


{{-- NAVBAR --}}
<header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3.5 sm:px-8">

        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-500 text-white shadow-sm shadow-sky-200 transition group-hover:bg-sky-600">
                <i class="fa-solid fa-stethoscope text-base"></i>
            </span>
            <div class="leading-none">
                <p class="text-base font-extrabold tracking-tight text-slate-900">MediReserv</p>
                <p class="text-[10px] font-medium text-slate-400 mt-0.5">Reservasi Dokter</p>
            </div>
        </a>

        <nav class="hidden items-center gap-6 md:flex">
            <a href="#cara-kerja" class="text-sm font-medium text-slate-600 transition hover:text-sky-600">Cara Kerja</a>
            <a href="#fitur"      class="text-sm font-medium text-slate-600 transition hover:text-sky-600">Fitur</a>
            <a href="#spesialisasi" class="text-sm font-medium text-slate-600 transition hover:text-sky-600">Spesialisasi</a>
        </nav>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-sky-600">
                    <i class="fa-solid fa-gauge-high text-xs"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="hidden rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 sm:inline-flex">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm shadow-sky-200 transition hover:bg-sky-600">
                    Daftar Gratis
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            @endauth
        </div>
    </div>
</header>


{{-- HERO --}}
<section class="hero-bg overflow-hidden px-5 py-16 sm:px-8 sm:py-24 lg:py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-2">

        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3.5 py-1 text-xs font-semibold text-sky-700">
                <span class="pulse-dot h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                Platform Reservasi Aktif
            </span>

            <h1 class="mt-5 text-4xl font-black leading-[1.1] tracking-tight text-slate-950 sm:text-5xl lg:text-[3.5rem]">
                Konsultasi dokter<br>
                <span class="text-sky-500">kapan saja,</span><br>
                tanpa antri lama.
            </h1>

            <p class="mt-5 max-w-lg text-base leading-7 text-slate-500 sm:text-lg">
                MediReserv menghubungkan pasien dengan dokter pilihan.
                Pilih jadwal, kirim keluhan, dan pantau status reservasi -
                semua dalam satu sistem yang rapi.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-sky-200 transition hover:bg-sky-600">
                        Buka Dashboard
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-sky-200 transition hover:bg-sky-600">
                        Daftar Sebagai Pasien
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i class="fa-solid fa-right-to-bracket text-slate-400"></i>
                        Masuk ke Akun
                    </a>
                @endauth
            </div>

            <div class="mt-10 flex flex-wrap gap-x-8 gap-y-3">
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">500+</p>
                    <p class="text-xs font-medium text-slate-500">Dokter Terdaftar</p>
                </div>
                <div class="w-px bg-slate-200"></div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">10rb+</p>
                    <p class="text-xs font-medium text-slate-500">Pasien Aktif</p>
                </div>
                <div class="w-px bg-slate-200"></div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">50rb+</p>
                    <p class="text-xs font-medium text-slate-500">Reservasi Berhasil</p>
                </div>
            </div>
        </div>

        {{-- Appointment Card Mockup --}}
        <div class="flex justify-center lg:justify-end">
            <div class="float-anim w-full max-w-[380px]">
                <div class="rounded-3xl bg-white p-4 shadow-2xl shadow-sky-100 ring-1 ring-slate-200/60">

                    <div class="mb-4 flex items-center justify-between rounded-2xl bg-slate-950 px-4 py-3.5">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">MediReserv</p>
                            <p class="mt-0.5 text-sm font-bold text-white">Buat Reservasi</p>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-emerald-400/15 px-2.5 py-1 text-[10px] font-bold text-emerald-300">
                            <span class="pulse-dot h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            Online
                        </span>
                    </div>

                    <div class="mb-3 flex items-center gap-3 rounded-2xl bg-sky-50 px-4 py-3.5">
                        <div class="grid h-12 w-12 flex-shrink-0 place-items-center rounded-2xl bg-sky-500 text-white shadow-sm">
                            <i class="fa-solid fa-user-doctor text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-900">dr. Andi Pratama, Sp.PD</p>
                            <p class="text-xs text-slate-500">Penyakit Dalam</p>
                        </div>
                        <span class="ml-auto flex-shrink-0 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                            Tersedia
                        </span>
                    </div>

                    <div class="mb-3 grid grid-cols-2 gap-2">
                        <div class="rounded-xl border border-sky-200 bg-sky-500 px-3 py-2.5 text-center text-white">
                            <p class="text-[10px] font-medium opacity-80">Tanggal</p>
                            <p class="mt-0.5 text-sm font-bold">Senin, 23 Jun</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 px-3 py-2.5 text-center">
                            <p class="text-[10px] font-medium text-slate-400">Jam</p>
                            <p class="mt-0.5 text-sm font-bold text-slate-900">09:00 - 10:00</p>
                        </div>
                    </div>

                    <div class="mb-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                        <p class="text-[10px] font-semibold text-slate-400">Keluhan</p>
                        <p class="mt-1 text-xs text-slate-500 italic">Demam sejak 2 hari, sakit kepala...</p>
                    </div>

                    <button class="w-full rounded-xl bg-sky-500 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600">
                        Konfirmasi Reservasi
                    </button>

                    <div class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-400">
                        <i class="fa-solid fa-shield-check text-sky-400"></i>
                        Data Anda aman & terlindungi
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- SPESIALISASI --}}
<section id="spesialisasi" class="border-y border-slate-100 bg-white px-5 py-8 sm:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="mb-4 text-center text-xs font-semibold uppercase tracking-widest text-slate-400">Spesialisasi Tersedia</p>
        <div class="flex flex-wrap justify-center gap-2">
            @php
            $spesialisasi = [
                ['icon' => 'fa-stethoscope',    'label' => 'Dokter Umum'],
                ['icon' => 'fa-heart-pulse',    'label' => 'Jantung'],
                ['icon' => 'fa-brain',          'label' => 'Saraf'],
                ['icon' => 'fa-baby',           'label' => 'Anak'],
                ['icon' => 'fa-venus',          'label' => 'Kandungan'],
                ['icon' => 'fa-eye',            'label' => 'Mata'],
                ['icon' => 'fa-tooth',          'label' => 'Gigi'],
                ['icon' => 'fa-lungs',          'label' => 'Paru-Paru'],
                ['icon' => 'fa-bone',           'label' => 'Orthopedi'],
                ['icon' => 'fa-spa',            'label' => 'Kulit'],
                ['icon' => 'fa-ear-listen',     'label' => 'THT'],
                ['icon' => 'fa-pills',          'label' => 'Penyakit Dalam'],
            ];
            @endphp

            @foreach($spesialisasi as $s)
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 cursor-default">
                <i class="fa-solid {{ $s['icon'] }} text-sky-500 text-xs"></i>
                {{ $s['label'] }}
            </span>
            @endforeach
        </div>
    </div>
</section>


{{-- CARA KERJA --}}
<section id="cara-kerja" class="bg-slate-950 px-5 py-20 sm:px-8 sm:py-24">
    <div class="mx-auto max-w-7xl">

        <div class="mb-14 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-sky-400">Mudah & Cepat</p>
            <h2 class="mt-3 text-3xl font-black text-white sm:text-4xl">Cara reservasi dokter</h2>
            <p class="mt-3 text-sm text-slate-400">Dari daftar hingga konfirmasi, hanya dalam beberapa langkah.</p>
        </div>

        <div class="grid gap-8 md:grid-cols-3">

            <div class="relative rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-sky-500/20 text-sky-400">
                        <i class="fa-solid fa-user-doctor text-xl"></i>
                    </span>
                    <span class="text-3xl font-black text-white/10">01</span>
                </div>
                <h3 class="mb-2 text-lg font-bold text-white">Pilih Dokter</h3>
                <p class="text-sm leading-6 text-slate-400">Cari dokter berdasarkan spesialisasi yang Anda butuhkan. Lihat profil dan jadwal praktik yang tersedia.</p>
                <div class="absolute -right-5 top-1/2 z-10 hidden -translate-y-1/2 text-slate-700 md:block">
                    <i class="fa-solid fa-chevron-right text-xl"></i>
                </div>
            </div>

            <div class="relative rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-400/20 text-amber-400">
                        <i class="fa-solid fa-calendar-days text-xl"></i>
                    </span>
                    <span class="text-3xl font-black text-white/10">02</span>
                </div>
                <h3 class="mb-2 text-lg font-bold text-white">Tentukan Jadwal</h3>
                <p class="text-sm leading-6 text-slate-400">Pilih hari dan jam praktik yang sesuai. Sistem otomatis mengecek ketersediaan kuota jadwal.</p>
                <div class="absolute -right-5 top-1/2 z-10 hidden -translate-y-1/2 text-slate-700 md:block">
                    <i class="fa-solid fa-chevron-right text-xl"></i>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-400/20 text-emerald-400">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </span>
                    <span class="text-3xl font-black text-white/10">03</span>
                </div>
                <h3 class="mb-2 text-lg font-bold text-white">Konfirmasi & Datang</h3>
                <p class="text-sm leading-6 text-slate-400">Reservasi masuk sebagai <em class="text-white not-italic font-semibold">Pending</em>. Dokter akan mengkonfirmasi, lalu Anda tinggal datang sesuai jadwal.</p>
            </div>

        </div>
    </div>
</section>


{{-- FITUR PER ROLE --}}
<section id="fitur" class="bg-white px-5 py-20 sm:px-8 sm:py-24">
    <div class="mx-auto max-w-7xl">

        <div class="mb-14 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-sky-600">Untuk Semua Pengguna</p>
            <h2 class="mt-3 text-3xl font-black text-slate-950 sm:text-4xl">Satu sistem, tiga peran</h2>
            <p class="mt-3 text-sm text-slate-500">MediReserv dirancang agar pasien, dokter, dan admin bisa bekerja dari satu platform yang terintegrasi.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">

            <div class="group rounded-2xl border border-slate-200 p-6 transition hover:border-sky-300 hover:shadow-lg hover:shadow-sky-50">
                <div class="mb-5 grid h-14 w-14 place-items-center rounded-2xl bg-sky-100 text-sky-600 transition group-hover:bg-sky-500 group-hover:text-white">
                    <i class="fa-solid fa-user text-2xl"></i>
                </div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-sky-500">Pasien</p>
                <h3 class="mb-3 text-xl font-bold text-slate-900">Reservasi kapan saja</h3>
                <p class="mb-5 text-sm leading-6 text-slate-500">Daftar, cari dokter, dan buat reservasi online. Pantau status kunjungan langsung dari dashboard.</p>
                <ul class="space-y-2.5">
                    @foreach(['Cari dokter & jadwal tersedia', 'Isi keluhan sebelum kunjungan', 'Pantau status reservasi real-time', 'Batalkan reservasi yang belum diproses'] as $item)
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <i class="fa-solid fa-check mt-0.5 text-sky-500"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <div class="mt-6">
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-sky-600 hover:underline">
                        Daftar sekarang <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="group rounded-2xl border border-slate-200 p-6 transition hover:border-sky-300 hover:shadow-lg hover:shadow-sky-50">
                <div class="mb-5 grid h-14 w-14 place-items-center rounded-2xl bg-amber-100 text-amber-600 transition group-hover:bg-amber-500 group-hover:text-white">
                    <i class="fa-solid fa-user-doctor text-2xl"></i>
                </div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-amber-500">Dokter</p>
                <h3 class="mb-3 text-xl font-bold text-slate-900">Kelola antrian pasien</h3>
                <p class="mb-5 text-sm leading-6 text-slate-500">Lihat daftar pasien yang sudah melakukan reservasi dan kelola status setiap kunjungan dengan mudah.</p>
                <ul class="space-y-2.5">
                    @foreach(['Lihat reservasi masuk per jadwal', 'Update status: Pending -> Hold -> Done', 'Baca keluhan pasien sebelum bertemu', 'Riwayat pasien tercatat otomatis'] as $item)
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <i class="fa-solid fa-check mt-0.5 text-amber-500"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-amber-600 hover:underline">
                        Masuk sebagai dokter <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="group rounded-2xl border border-slate-200 p-6 transition hover:border-sky-300 hover:shadow-lg hover:shadow-sky-50">
                <div class="mb-5 grid h-14 w-14 place-items-center rounded-2xl bg-violet-100 text-violet-600 transition group-hover:bg-violet-500 group-hover:text-white">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-violet-500">Admin</p>
                <h3 class="mb-3 text-xl font-bold text-slate-900">Kontrol penuh klinik</h3>
                <p class="mb-5 text-sm leading-6 text-slate-500">Tambah dokter, atur jadwal praktik, dan monitor semua reservasi serta pengguna dari satu panel terpusat.</p>
                <ul class="space-y-2.5">
                    @foreach(['Kelola data dokter & spesialisasi', 'Atur jadwal praktik & kuota', 'Monitor semua reservasi & status', 'Manajemen akun seluruh pengguna'] as $item)
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <i class="fa-solid fa-check mt-0.5 text-violet-500"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-violet-600 hover:underline">
                        Masuk sebagai admin <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- STATUS TRACKING --}}
<section class="border-y border-slate-100 bg-slate-50 px-5 py-14 sm:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid items-center gap-10 md:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-sky-600">Tracking Real-Time</p>
                <h2 class="mt-3 text-2xl font-black text-slate-950 sm:text-3xl">Pantau status reservasi<br>dari mana saja</h2>
                <p class="mt-4 text-sm leading-7 text-slate-500">
                    Setiap reservasi memiliki status yang di-update langsung oleh dokter.
                    Pasien tidak perlu menelepon untuk tahu apakah kunjungannya sudah dikonfirmasi.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Pending</p>
                    </div>
                    <p class="text-xs text-slate-500">Reservasi masuk, menunggu konfirmasi dokter.</p>
                </div>
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Hold</p>
                    </div>
                    <p class="text-xs text-slate-500">Dokter sedang memproses, segera dikonfirmasi.</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Done</p>
                    </div>
                    <p class="text-xs text-slate-500">Kunjungan selesai, pasien sudah ditangani.</p>
                </div>
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-red-400"></span>
                        <p class="text-xs font-bold uppercase tracking-wider text-red-600">Cancelled</p>
                    </div>
                    <p class="text-xs text-slate-500">Reservasi dibatalkan oleh pasien atau dokter.</p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- CTA --}}
<section class="bg-sky-600 px-5 py-20 sm:px-8 sm:py-24">
    <div class="mx-auto max-w-3xl text-center">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold text-sky-50">
            <i class="fa-solid fa-stethoscope"></i>
            Gratis untuk Pasien
        </span>
        <h2 class="mt-5 text-3xl font-black leading-tight text-white sm:text-4xl">
            Mulai reservasi dokter<br>pertama Anda sekarang.
        </h2>
        <p class="mt-4 text-sm leading-7 text-sky-100">
            Daftar gratis dalam hitungan detik. Tidak perlu kartu kredit.
            Langsung bisa cari dokter dan buat reservasi hari ini.
        </p>
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-bold text-sky-600 shadow-md transition hover:bg-sky-50">
                    Buka Dashboard <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-bold text-sky-600 shadow-md transition hover:bg-sky-50">
                    Daftar Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">
                    Sudah punya akun
                </a>
            @endauth
        </div>
    </div>
</section>


{{-- FOOTER --}}
<footer class="bg-slate-950 px-5 py-12 sm:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">

            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-500 text-white">
                        <i class="fa-solid fa-stethoscope"></i>
                    </span>
                    <span class="text-base font-extrabold text-white">MediReserv</span>
                </a>
                <p class="mt-3 max-w-sm text-sm leading-6 text-slate-400">
                    Platform reservasi dokter online yang menghubungkan pasien dengan dokter terpercaya. Mudah, cepat, dan transparan.
                </p>
            </div>

            <div>
                <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Navigasi</p>
                <ul class="space-y-2.5">
                    @foreach([
                        ['label' => 'Cara Kerja', 'href' => '#cara-kerja'],
                        ['label' => 'Fitur',      'href' => '#fitur'],
                        ['label' => 'Spesialisasi','href' => '#spesialisasi'],
                    ] as $link)
                    <li>
                        <a href="{{ $link['href'] }}" class="text-sm text-slate-400 transition hover:text-white">
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Akun</p>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('login') }}" class="text-sm text-slate-400 transition hover:text-white">Masuk</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="text-sm text-slate-400 transition hover:text-white">Daftar Pasien</a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="mt-10 border-t border-white/10 pt-6 text-center text-xs text-slate-600">
            &copy; {{ date('Y') }} MediReserv. Sistem Reservasi Dokter Online.
        </div>

    </div>
</footer>


</body>
</html>
