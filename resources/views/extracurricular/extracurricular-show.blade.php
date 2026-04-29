@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-lg font-bold text-blue-700">
                Detail Ekstrakurikuler
            </h1>
            <p class="text-sm text-gray-500">
                Informasi lengkap ekskul
            </p>
        </div>

        <a href="{{ route('extracurricular.index') }}"
           class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>

    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">

            <!-- NAMA -->
            <h1 class="text-2xl font-bold mb-2">
                {{ $data->name }}
            </h1>

            <!-- PEMBINA -->
            <p class="text-gray-600 mb-4">
                Pembina:
                <span class="font-semibold">
                    {{ $data->teacher->name ?? '-' }}
                </span>
            </p>

            <!-- SISWA -->
            <h3 class="font-semibold mb-3">Peserta:</h3>

            <div class="grid grid-cols-2 gap-2">

                @forelse($data->students as $s)
                    <div class="bg-gray-100 p-2 rounded">
                        {{ $s->name }}
                    </div>
                @empty
                    <p class="text-gray-500 col-span-2">
                        Belum ada peserta
                    </p>
                @endforelse

            </div>

        </div>

    </main>

</div>

@endsection