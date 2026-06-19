@extends('admin.layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data sistem reservasi dokter')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Total Dokter --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dokter</p>
            <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-user-doctor text-sky-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalDokter }}</p>
        <p class="text-xs text-gray-400 mt-1">Dokter terdaftar</p>
    </div>

    {{-- Total Pasien --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pasien</p>
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-users text-violet-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalPasien }}</p>
        <p class="text-xs text-gray-400 mt-1">Pasien terdaftar</p>
    </div>

    {{-- Reservasi Hari Ini --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reservasi Hari Ini</p>
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-amber-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $reservasiHariIni }}</p>
        <p class="text-xs text-gray-400 mt-1">Pada {{ \Carbon\Carbon::today()->isoFormat('D MMM Y') }}</p>
    </div>

    {{-- Reservasi Pending --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reservasi Pending</p>
            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-rose-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $reservasiPending }}</p>
        <p class="text-xs text-gray-400 mt-1">Menunggu konfirmasi</p>
    </div>

</div>

{{-- Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Reservasi Terbaru --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Reservasi Terbaru</h2>
            <a href="{{ route('admin.reservasi.index') }}" class="text-xs text-sky-500 hover:text-sky-600 font-medium">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reservasiTerbaru as $r)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $r->user->name }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $r->schedule->doctor->user->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ \Carbon\Carbon::parse($r->schedule->date)->isoFormat('D MMM Y') }}</td>
                        <td class="px-6 py-3">
                            @if($r->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Pending</span>
                            @elseif($r->status == 'done')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Done</span>
                            @elseif($r->status == 'hold')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700">Hold</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada reservasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Dokter Aktif --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Dokter Aktif</h2>
            <a href="{{ route('admin.dokter.index') }}" class="text-xs text-sky-500 hover:text-sky-600 font-medium">Kelola →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($dokterAktif as $d)
            <div class="flex items-center gap-3 px-6 py-3">
                <div class="w-9 h-9 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0">
                    @if($d->photo)
                        <img src="{{ asset('storage/'.$d->photo) }}" class="w-9 h-9 rounded-full object-cover">
                    @else
                        <i class="fa-solid fa-user-doctor text-sky-500 text-sm"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $d->user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $d->specialization }}</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada dokter</div>
            @endforelse
        </div>
    </div>

</div>

@endsection
