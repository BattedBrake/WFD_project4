<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <main class="min-h-screen grid lg:grid-cols-[1.05fr_0.95fr]">
        <section class="hidden lg:flex relative overflow-hidden bg-sky-600 bg-cover bg-center px-12 py-10 text-white"
            style="background-image: url('{{ asset('images/loginmedical.jpg') }}')">
            <div class="absolute inset-0 bg-sky-950/70"></div>
            <div class="relative z-10 flex w-full flex-col">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/15 ring-1 ring-white/20">
                        <i class="fa-solid fa-stethoscope text-xl"></i>
                    </span>
                    <span class="text-lg font-bold">MediReserv</span>
                </a>

                <div class="my-auto max-w-xl">
                    <p class="mb-4 text-sm font-semibold uppercase tracking-wider text-sky-100">Reservasi Dokter Online</p>
                    <h1 class="text-5xl font-extrabold leading-tight">Kelola jadwal dan reservasi klinik dengan lebih rapi.</h1>
                    <p class="mt-5 text-base leading-7 text-sky-50">Masuk untuk mengatur jadwal, memantau reservasi, dan melanjutkan layanan kesehatan dengan lebih praktis.</p>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="mb-8 lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-500 text-white">
                            <i class="fa-solid fa-stethoscope"></i>
                        </span>
                        <span class="text-lg font-bold">MediReserv</span>
                    </a>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-7">
                        <p class="text-sm font-semibold text-sky-600">Selamat datang</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900">Masuk ke akun</h2>
                        <p class="mt-2 text-sm text-slate-500">Gunakan akun yang sudah terdaftar untuk melanjutkan.</p>
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

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                placeholder="admin@example.com">
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                placeholder="password">
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-500">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-sky-500 focus:ring-sky-300">
                            Ingat saya
                        </label>

                        <button type="submit" class="w-full rounded-xl bg-sky-500 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Masuk
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-sky-600 hover:text-sky-700">Daftar pasien</a>
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
