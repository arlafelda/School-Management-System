@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">
        <h2 class="font-semibold text-blue-700">
            Tambah Ekstrakurikuler
        </h2>

        <a href="{{ route('extracurricular.index') }}"
           class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>
    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <div class="bg-white p-6 rounded-lg shadow max-w-3xl mx-auto">

            <form method="POST" action="{{ route('extracurricular.store') }}">
                @csrf

                <!-- NAMA -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">Nama Ekskul</label>
                    <input type="text" name="name"
                           class="w-full border rounded-lg p-2 focus:outline-none focus:ring"
                           required>
                </div>

                <!-- PEMBINA -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">Pembina</label>
                    <select name="teacher_id"
                            class="w-full border rounded-lg p-2 focus:outline-none focus:ring">

                        <option value="">-- Pilih Guru --</option>

                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach

                    </select>
                </div>

                <!-- SISWA -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium">Pilih Siswa</label>

                    <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border p-3 rounded bg-gray-50">

                        @foreach($students as $s)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="student_ids[]" value="{{ $s->id }}">
                                {{ $s->name }}
                            </label>
                        @endforeach

                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-between mt-6">

                    <a href="{{ route('extracurricular.index') }}"
                       class="text-gray-600 hover:text-gray-800 text-sm">
                        ← Kembali
                    </a>

                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

@endsection