<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-800">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden bg-cover bg-center px-5 py-10"
        style="background-image: url('{{ asset('images/registerpage.jpg') }}')">
        <div class="absolute inset-0 bg-sky-950/70"></div>

        <div class="relative z-10 w-full max-w-lg">
            <div class="mb-7 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/15 text-white ring-1 ring-white/20">
                        <i class="fa-solid fa-stethoscope"></i>
                    </span>
                    <span class="text-xl font-bold text-white">MediReserv</span>
                </a>
                <h1 class="mt-8 text-2xl font-bold text-white">Daftar akun pasien</h1>
                <p class="mt-2 text-sm text-sky-50">Akun baru otomatis dibuat sebagai role pasien.</p>
            </div>

            <div class="rounded-2xl border border-white/20 bg-white p-6 shadow-2xl shadow-sky-950/30 sm:p-8">
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                            placeholder="Nama pasien">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                            placeholder="nama@email.com">
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-sky-500 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                        Daftar
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-sky-600 hover:text-sky-700">Masuk</a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>
