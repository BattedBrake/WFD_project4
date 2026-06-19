@extends('admin.layouts.app')

@section('page-title', isset($dokter) ? 'Edit Dokter' : 'Tambah Dokter')
@section('page-subtitle', isset($dokter) ? 'Ubah data dokter yang sudah ada' : 'Daftarkan dokter baru ke dalam sistem')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">{{ isset($dokter) ? 'Form Edit Dokter' : 'Form Tambah Dokter' }}</h2>
        </div>

        <form method="POST"
              action="{{ isset($dokter) ? route('admin.dokter.update', $dokter->id) : route('admin.dokter.store') }}"
              enctype="multipart/form-data"
              class="px-6 py-6 space-y-5">
            @csrf
            @if(isset($dokter)) @method('PUT') @endif

            {{-- Nama --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $dokter->user->name ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('name') border-red-400 @enderror"
                    placeholder="dr. Nama Dokter, Sp.XX">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $dokter->user->email ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('email') border-red-400 @enderror"
                    placeholder="dokter@email.com">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Password (only for create) --}}
            @if(!isset($dokter))
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('password') border-red-400 @enderror"
                    placeholder="Minimal 8 karakter">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            @endif

            {{-- No. Telepon --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $dokter->user->phone ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300"
                    placeholder="08xxxxxxxxxx">
            </div>

            {{-- Spesialisasi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Spesialisasi <span class="text-red-500">*</span></label>
                <input type="text" name="specialization" value="{{ old('specialization', $dokter->specialization ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('specialization') border-red-400 @enderror"
                    placeholder="Contoh: Spesialis Jantung, Dokter Umum">
                @error('specialization')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 resize-none"
                    placeholder="Tuliskan deskripsi singkat tentang dokter...">{{ old('description', $dokter->description ?? '') }}</textarea>
            </div>

            {{-- Foto --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Foto Profil</label>
                @if(isset($dokter) && $dokter->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$dokter->photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
                <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG. Maksimal 2MB.</p>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    {{ isset($dokter) ? 'Simpan Perubahan' : 'Tambah Dokter' }}
                </button>
                <a href="{{ route('admin.dokter.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
