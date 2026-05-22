@extends('layouts.app')

@section('content')

@php
$user = auth()->user();
@endphp

<div class="min-h-screen bg-gray-100 text-gray-800">

    <div class="px-6 pt-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            List Kelas
        </span>
    </div>

    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-blue-700 font-[Manrope]">
                Master Data Kelas
            </h1>

            <p class="text-sm text-gray-500">
                Kelola data kelas sekolah
            </p>
        </div>

        <div class="flex gap-2">

            @if(in_array($user->role, ['super_admin', 'admin']))
            <a href="{{ route('class.archived') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Arsip
            </a>

            <a href="{{ route('class.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                + Tambah Kelas
            </a>
            @endif

        </div>

    </header>

    <main class="p-6">

        <div id="alertBox"></div>

        {{-- FILTER --}}
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <form method="GET" action="{{ route('class.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama kelas..."
                           class="border rounded-lg px-3 py-2 w-full">

                    <select name="level"
                            class="border rounded-lg px-3 py-2 w-full">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('level') == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ request('level') == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ request('level') == '12' ? 'selected' : '' }}>12</option>
                    </select>

                    <input type="text"
                           name="major"
                           value="{{ request('major') }}"
                           placeholder="Filter jurusan..."
                           class="border rounded-lg px-3 py-2 w-full">

                    <div class="flex gap-2">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Filter
                        </button>

                        <a href="{{ route('class.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-4 text-left">Nama Kelas</th>
                        <th class="p-4 text-center">Tingkat</th>
                        <th class="p-4 text-center">Jurusan</th>
                        <th class="p-4 text-left">Wali Kelas</th>
                        <th class="p-4 text-center">Jumlah Siswa</th>

                        @if(in_array($user->role, ['super_admin', 'admin']))
                        <th class="p-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($classes as $class)

                    <tr id="row-{{ $class->id }}"
                        class="hover:bg-gray-50 transition">

                        <td class="p-4 font-semibold">
                            <a href="{{ route('class.show', $class->slug) }}"
                               class="text-blue-600 hover:underline">
                                {{ $class->name }}
                            </a>
                        </td>

                        <td class="p-4 text-center">
                            {{ $class->level }}
                        </td>

                        <td class="p-4 text-center">
                            {{ $class->major ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $class->teacher->name ?? 'Belum ada wali kelas' }}
                        </td>

                        <td class="p-4 text-center">
                            {{ $class->students->count() }}
                        </td>

                        @if(in_array($user->role, ['super_admin', 'admin']))
                        <td class="p-4 text-center space-x-3">

                            <a href="{{ route('class.edit', $class->slug) }}"
                               class="text-blue-600 hover:underline text-sm">
                                Edit
                            </a>

                            <form action="{{ route('class.delete', $class->slug) }}"
                                  method="POST"
                                  class="inline formDelete">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-500 hover:underline text-sm">
                                    Arsipkan
                                </button>

                            </form>

                        </td>
                        @endif

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center p-6 text-gray-500">
                            Data kelas belum tersedia
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </main>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('submit', function (e) {

        if (!e.target.classList.contains('formDelete')) return;

        e.preventDefault();

        if (!confirm('Yakin ingin memindahkan data ke arsip?')) return;

        let form = e.target;
        let url = form.action;
        let row = form.closest('tr');

        if (typeof deleteData !== 'undefined') {

            deleteData(url, function (res) {

                if (row) row.remove();

                document.getElementById('alertBox').innerHTML = `
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        ${res.message ?? 'Data berhasil dipindahkan ke arsip'}
                    </div>
                `;
            });

        } else {
            console.error('deleteData belum tersedia');
        }

    });

});
</script>
@endpush