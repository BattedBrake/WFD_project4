@extends('admin.layouts.app')

@section('page-title', 'Kelola Reservasi')
@section('page-subtitle', 'Pantau dan kelola semua reservasi pasien')

@section('content')

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 mb-5">
    <form method="GET" action="{{ route('admin.reservasi.index') }}" class="flex flex-wrap gap-3 items-center">
        <div class="relative">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama pasien / dokter..."
                class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 w-56">
        </div>

        <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
            <option value="hold" {{ request('status')=='hold' ? 'selected' : '' }}>Hold</option>
            <option value="done" {{ request('status')=='done' ? 'selected' : '' }}>Done</option>
            <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <input type="date" name="date" value="{{ request('date') }}"
            class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">

        <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Cari
        </button>
        <a href="{{ route('admin.reservasi.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keluhan</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reservasis as $r)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 text-gray-400 font-mono text-xs">#{{ $r->id }}</td>
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $r->user->name }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $r->schedule->doctor->user->name }}</td>
                    <td class="px-6 py-3 text-gray-600">
                        {{ \Carbon\Carbon::parse($r->schedule->date)->isoFormat('D MMM Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $r->schedule->start_time }} – {{ $r->schedule->end_time }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-600 max-w-[180px] truncate" title="{{ $r->complaint }}">
                        {{ $r->complaint }}
                    </td>
                    <td class="px-6 py-3">
                        @php
                            $statusConfig = [
                                'pending'   => 'bg-amber-100 text-amber-700',
                                'hold'      => 'bg-sky-100 text-sky-700',
                                'done'      => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig[$r->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $r->status }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.reservasi.show', $r->id) }}"
                               class="p-1.5 text-gray-400 hover:text-sky-500 hover:bg-sky-50 rounded-lg transition-all" title="Detail">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.reservasi.destroy', $r->id) }}"
                                  onsubmit="return confirm('Hapus reservasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">
                        <i class="fa-solid fa-clipboard-list text-3xl mb-3 block opacity-30"></i>
                        Tidak ada data reservasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reservasis->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $reservasis->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
