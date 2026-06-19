@extends('admin.layouts.app')

@section('page-title', 'Edit User')
@section('page-subtitle', 'Ubah data pengguna')

@section('content')

<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Form Edit User</h2>
        </div>

        <form method="POST" action="{{ route('admin.user.update', $user->id) }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('name') border-red-400 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('email') border-red-400 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 @error('role') border-red-400 @enderror">
                    <option value="pasien"  {{ old('role', $user->role) == 'pasien'  ? 'selected' : '' }}>Pasien</option>
                    <option value="dokter"  {{ old('role', $user->role) == 'dokter'  ? 'selected' : '' }}>Dokter</option>
                    <option value="admin"   {{ old('role', $user->role) == 'admin'   ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.user.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
