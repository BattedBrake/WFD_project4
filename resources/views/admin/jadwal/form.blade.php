@extends('admin.layouts.app')

@section('page-title', isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('page-subtitle', isset($jadwal) ? 'Ubah jadwal praktek dokter' : 'Buat jadwal praktek baru')

@section('content')

<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">{{ isset($jadwal) ? 'Form Edit Jadwal' : 'Form Tambah Jadwal' }}</h2>
        </div>

        <form method="POST"
              action="{{ isset($jadwal) ? route('admin.jadwal.update', $jadwal->id) : route('admin.jadwal.store') }}"
              class="px-6 py-6 space-y-5">
            @csrf
            @if(isset($jadwal)) @method('PUT') @endif

            {{-- Dokter --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dokter <span class="text-red-500">*</span></label>
                <select name="doctor_id"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('doctor_id') border-red-400 @enderror">
                    <option value="">Pilih Dokter</option>
                    @foreach($dokters as $d)
                        <option value="{{ $d->id }}"
                            {{ old('doctor_id', $jadwal->doctor_id ?? '') == $d->id ? 'selected' : '' }}>
                            {{ $d->user->name }} - {{ $d->specialization }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ old('date', $jadwal->date ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('date') border-red-400 @enderror">
                @error('date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Waktu --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time', $jadwal->start_time ?? '') }}"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('start_time') border-red-400 @enderror">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time', $jadwal->end_time ?? '') }}"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('end_time') border-red-400 @enderror">
                </div>
            </div>

            {{-- Kuota --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kuota Pasien <span class="text-red-500">*</span></label>
                <input type="number" name="quota" value="{{ old('quota', $jadwal->quota ?? '') }}" min="1"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('quota') border-red-400 @enderror"
                    placeholder="Contoh: 10">
                @error('quota')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    {{ isset($jadwal) ? 'Simpan Perubahan' : 'Tambah Jadwal' }}
                </button>
                <a href="{{ route('admin.jadwal.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
