@extends('admin.layouts.app')

@section('page-title', 'Kelola User')
@section('page-subtitle', 'Manajemen semua pengguna dalam sistem')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Daftar User</h2>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau email..."
                        class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300 w-52">
                </div>
                <select name="role" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <option value="">Semua Role</option>
                    <option value="pasien" {{ request('role')=='pasien' ? 'selected' : '' }}>Pasien</option>
                    <option value="dokter" {{ request('role')=='dokter' ? 'selected' : '' }}>Dokter</option>
                    <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    Filter
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Telepon</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-gray-500">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $u->email }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $u->phone ?? '-' }}</td>
                    <td class="px-6 py-3">
                        @php
                            $roleConfig = [
                                'admin'  => 'bg-purple-100 text-purple-700',
                                'dokter' => 'bg-sky-100 text-sky-700',
                                'pasien' => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleConfig[$u->role] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500 text-xs">
                        {{ $u->created_at->isoFormat('D MMM Y') }}
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.user.edit', $u->id) }}"
                               class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </a>
                            @if($u->id !== Auth::id())
                            <form method="POST" action="{{ route('admin.user.destroy', $u->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                        <i class="fa-solid fa-users text-3xl mb-3 block opacity-30"></i>
                        Tidak ada data user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
