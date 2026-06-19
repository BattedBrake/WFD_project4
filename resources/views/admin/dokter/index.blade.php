@extends('admin.layouts.app')

@section('page-title', 'Kelola Dokter')
@section('page-subtitle', 'Manajemen data dokter dalam sistem')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Daftar Dokter</h2>
        <a href="{{ route('admin.dokter.create') }}"
           class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Dokter
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <form method="GET" action="{{ route('admin.dokter.index') }}" class="flex gap-3">
            <div class="relative flex-1 max-w-xs">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama dokter..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-sky-400">
            </div>
            <select name="specialization" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
                <option value="">Semua Spesialisasi</option>
                @foreach($spesialisasiList as $sp)
                    <option value="{{ $sp }}" {{ request('specialization') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Telepon</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($dokters as $d)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($d->photo)
                                    <img src="{{ asset('storage/'.$d->photo) }}" class="w-9 h-9 object-cover">
                                @else
                                    <i class="fa-solid fa-user-doctor text-sky-500 text-sm"></i>
                                @endif
                            </div>
                            <span class="font-medium text-gray-800">{{ $d->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-100">
                            {{ $d->specialization }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $d->user->email }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $d->user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.dokter.show', $d->id) }}"
                               class="p-1.5 text-gray-400 hover:text-sky-500 hover:bg-sky-50 rounded-lg transition-all" title="Detail">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.dokter.edit', $d->id) }}"
                               class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.dokter.destroy', $d->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                        <i class="fa-solid fa-user-doctor text-3xl mb-3 block opacity-30"></i>
                        Belum ada data dokter
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($dokters->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $dokters->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
