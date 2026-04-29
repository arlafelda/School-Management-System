@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">
        <div>
            <h2 class="font-semibold text-blue-700">
                Edit Ekstrakurikuler
            </h2>
            <p class="text-sm text-gray-500">
                Perbarui data ekskul
            </p>
        </div>

        <a href="{{ route('extracurricular.index') }}"
           class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>
    </header>

    <!-- CONTENT -->
    <main class="p-6 flex justify-center">

        <div class="w-full max-w-3xl bg-white rounded-lg shadow p-6">

            <!-- ERROR VALIDATION -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-600 p-3 rounded-lg">
                    <ul class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORM -->
            <form action="{{ route('extracurricular.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- NAMA EKSKUL -->
                <div class="mb-4">
                    <label class="block text-sm mb-1 font-medium">Nama Ekstrakurikuler</label>
                    <input type="text" name="name"
                        value="{{ old('name', $data->name) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
                        placeholder="Contoh: Futsal, Pramuka">
                </div>

                <!-- GURU PEMBINA -->
                <div class="mb-4">
                    <label class="block text-sm mb-1 font-medium">Pembina (Guru)</label>
                    <select name="teacher_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none">

                        <option value="">-- Pilih Guru --</option>

                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ $data->teacher_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} - {{ $teacher->subject }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- SISWA -->
                <div class="mb-6">
                    <label class="block text-sm mb-2 font-medium">Anggota Siswa</label>

                    <div class="max-h-60 overflow-y-auto border rounded-lg p-3 grid grid-cols-1 sm:grid-cols-2 gap-2">

                        @foreach($students as $student)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox"
                                    name="student_ids[]"
                                    value="{{ $student->id }}"
                                    {{ in_array($student->id, $data->students->pluck('id')->toArray()) ? 'checked' : '' }}
                                    class="rounded text-blue-600">

                                {{ $student->name }}
                            </label>
                        @endforeach

                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-between">

                    <a href="{{ route('extracurricular.index') }}"
                       class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm">
                        Kembali
                    </a>

                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

@endsection