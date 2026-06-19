@extends('admin.layouts.app')

@section('page-title', 'Jadwal Praktek')
@section('page-subtitle', 'Kelola jadwal praktek seluruh dokter')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Daftar Jadwal</h2>
        <a href="{{ route('admin.jadwal.create') }}"
           class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Jadwal
        </a>
    </div>

    {{-- Filter --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="doctor_id" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
                <option value="">Semua Dokter</option>
                @foreach($dokters as $d)
                    <option value="{{ $d->id }}" {{ request('doctor_id')==$d->id ? 'selected' : '' }}>
                        {{ $d->user->name }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.jadwal.index') }}" class="text-sm text-gray-400 hover:text-gray-600 py-2">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kuota</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Terisi</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($jadwals as $j)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $j->doctor->user->name }}</td>
                    <td class="px-6 py-3 text-gray-600">
                        {{ \Carbon\Carbon::parse($j->date)->isoFormat('dddd, D MMM Y') }}
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $j->start_time }} – {{ $j->end_time }}</td>
                    <td class="px-6 py-3">
                        <span class="font-semibold text-gray-700">{{ $j->quota }}</span>
                        <span class="text-gray-400 text-xs"> pasien</span>
                    </td>
                    <td class="px-6 py-3">
                        @php $terisi = $j->reservations->count(); @endphp
                        <div class="flex items-center gap-2">
                            <div class="w-16 bg-gray-100 rounded-full h-1.5">
                                <div class="bg-sky-400 h-1.5 rounded-full"
                                     style="width: {{ $j->quota > 0 ? min(100, ($terisi/$j->quota)*100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $terisi }}/{{ $j->quota }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.jadwal.edit', $j->id) }}"
                               class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal.destroy', $j->id) }}"
                                  onsubmit="return confirm('Hapus jadwal ini?')">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                        <i class="fa-solid fa-calendar-days text-3xl mb-3 block opacity-30"></i>
                        Belum ada jadwal praktek
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jadwals->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $jadwals->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
