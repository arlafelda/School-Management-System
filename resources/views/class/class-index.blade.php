@extends('layouts.app')

@section('title', 'Master Data Kelas')

@section('content')
@php $user = auth()->user(); @endphp

<div class="min-h-screen bg-gray-50">

    {{-- BREADCRUMB --}}
    <div class="px-6 pt-6 text-sm text-gray-500 flex items-center gap-2">
        <a href="{{ route('dashboard.super_admin') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-700 font-medium">Master Data Kelas</span>
    </div>

    {{-- HEADER --}}
    <div class="px-6 mt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800">Daftar Kelas</h1>
            <p class="text-gray-500 mt-1">Kelola data kelas sekolah yang terdaftar di sistem.</p>
        </div>

        <div class="flex items-center gap-3">
            @if(in_array($user->role, ['super_admin', 'admin']))
                <a href="{{ route('class.archived') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.595 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.595-1.858L5 7" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 7V4a2 2 0 00-2-2H8a2 2 0 00-2 2v3" />
                    </svg>
                    Arsip
                </a>

                <a href="{{ route('class.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-2xl hover:bg-blue-700 transition shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kelas
                </a>
            @endif
        </div>
    </div>

    <div class="px-6 mt-8">
        <div id="alertBox"></div>

        {{-- FILTER --}}
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 mb-6">
            <form method="GET" action="{{ route('class.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama kelas..." 
                           class="border border-gray-200 rounded-2xl px-5 py-3 w-full focus:outline-none focus:border-blue-500">

                    <select name="level" class="border border-gray-200 rounded-2xl px-5 py-3 w-full focus:outline-none focus:border-blue-500">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('level') == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ request('level') == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ request('level') == '12' ? 'selected' : '' }}>12</option>
                    </select>

                    <input type="text" name="major" value="{{ request('major') }}" 
                           placeholder="Filter jurusan..." 
                           class="border border-gray-200 rounded-2xl px-5 py-3 w-full focus:outline-none focus:border-blue-500">

                    <div class="flex gap-3">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-2xl transition">
                            Filter
                        </button>
                        <a href="{{ route('class.index') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-2xl text-center transition">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tingkat</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                        @if(in_array($user->role, ['super_admin', 'admin']))
                            <th class="px-6 py-5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($classes as $class)
                    <tr id="row-{{ $class->id }}" class="hover:bg-gray-50 transition">
                        <td class="px-6 py-5">
                            <a href="{{ route('class.show', $class->slug) }}" 
                               class="font-semibold text-gray-800 hover:text-blue-600 transition">
                                {{ $class->name }}
                            </a>
                        </td>
                        <td class="px-6 py-5 text-center font-medium">{{ $class->level }}</td>
                        <td class="px-6 py-5 text-center">{{ $class->major ?? '-' }}</td>

                        {{-- PERBAIKAN: Hapus raw HTML --}}
                        <td class="px-6 py-5 text-gray-600">
                            @if($class->teacher)
                                {{ $class->teacher->name }}
                            @else
                                <span class="text-gray-400 italic">Belum ada wali kelas</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-center font-semibold text-gray-700">
                            {{ $class->students->count() }}
                        </td>
                        
                        @if(in_array($user->role, ['super_admin', 'admin']))
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('class.edit', $class->slug) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-2xl transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </a>
                                
                                <form action="{{ route('class.destroy', $class->slug) }}" method="POST" class="inline formDelete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-2xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.595 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.595-1.858L5 7" />
                                        </svg>
                                        Arsipkan
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <p class="text-gray-400">Belum ada data kelas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', function (e) {
        if (!e.target.classList.contains('formDelete')) return;
        e.preventDefault();

        const form = e.target;
        const row = form.closest('tr');

        deleteData(form.action, 'Yakin ingin memindahkan kelas ini ke arsip?', {
            onSuccess: function (res) {
                row?.remove();
                const alertBox = document.getElementById('alertBox');
                alertBox.innerHTML = `
                    <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl text-sm">
                        ${res.message ?? 'Data berhasil dipindahkan ke arsip'}
                    </div>
                `;
                setTimeout(() => alertBox.innerHTML = '', 4000);
            }
        });
    });
});
</script>
@endpush