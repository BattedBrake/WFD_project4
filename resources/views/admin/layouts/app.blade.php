<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sistem Reservasi Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background-color: #0ea5e9; color: white; }
        .sidebar-link:hover:not(.active) { background-color: #f0f9ff; color: #0ea5e9; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white shadow-lg flex flex-col flex-shrink-0">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
            <div class="bg-sky-500 rounded-xl p-2">
                <i class="fa-solid fa-stethoscope text-white text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800 leading-tight">MediReserv</p>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.dokter.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.dokter.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fa-solid fa-user-doctor w-5 text-center"></i>
                Kelola Dokter
            </a>

            <a href="{{ route('admin.jadwal.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fa-solid fa-calendar-days w-5 text-center"></i>
                Jadwal Praktek
            </a>

            <a href="{{ route('admin.reservasi.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                Kelola Reservasi
            </a>

            <a href="{{ route('admin.user.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                <i class="fa-solid fa-users w-5 text-center"></i>
                Kelola User
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3 px-3 py-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center">
                    <i class="fa-solid fa-user text-sky-600 text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-700 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition-all font-medium">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle', 'Selamat datang di panel administrasi')</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="flex-1 overflow-y-auto px-8 py-6">
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>

</body>
</html>
