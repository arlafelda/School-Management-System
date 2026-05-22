@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- BREADCRUMB -->
    <div class="px-6 pt-4 text-sm text-gray-500">
        <a href="{{ route('extracurricular.index') }}"
           class="hover:text-blue-600">
            Master Data
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Archived Extracurricular
        </span>
    </div>

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-xl font-bold text-gray-700">
                Archived Extracurricular
            </h1>

            <p class="text-sm text-gray-500">
                Data ekstrakurikuler yang diarsipkan
            </p>
        </div>

        <a href="{{ route('extracurricular.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            ← Kembali
        </a>

    </header>

    <!-- MAIN -->
    <main class="p-6">

        <!-- ALERT SUCCESS -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- ALERT ERROR -->
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nama Ekskul</th>
                        <th class="p-3 text-center">Pembina</th>
                        <th class="p-3 text-right">Jumlah Siswa</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $d)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-3 font-medium">
                            {{ $d->name }}
                        </td>

                        <td class="p-3 text-center">
                            {{ $d->teacher->name ?? '-' }}
                        </td>

                        <td class="p-3 text-right">
                            {{ $d->students->count() }}
                        </td>

                        <td class="p-3 text-center">

                            <div class="flex justify-center gap-2">

                                <!-- Restore -->
                                <form action="{{ route('extracurricular.restore', $d->slug) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('PUT')

                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin restore data ini?')"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                        Restore
                                    </button>

                                </form>

                                <!-- Delete -->
                                <form action="{{ route('extracurricular.delete', $d->slug) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Hapus permanen data ini? Data tidak bisa dikembalikan!')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4"
                            class="text-center py-6 text-gray-400">
                            Tidak ada data archive
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </main>

</div>

@endsection