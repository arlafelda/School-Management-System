@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- 🧭 BREADCRUMB -->
    <div class="px-6 pt-4 text-sm text-gray-500">
        <a href="{{ route('extracurricular.index') }}" class="hover:text-blue-600">
            Ekstrakurikuler
        </a>
        <span class="mx-2">/</span>
        <a href="{{ route('extracurricular.show', $data->slug) }}" class="hover:text-blue-600">
            {{ $data->name }}
        </a>
        <span class="mx-2">/</span>
        <span class="text-gray-700 font-medium">
            Edit
        </span>
    </div>

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h2 class="font-semibold text-blue-700 text-xl">
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

        <!-- ALERT -->
        <div id="alertBox" class="absolute top-20 w-full max-w-3xl mx-auto"></div>

        <div class="w-full max-w-3xl bg-white rounded-lg shadow p-6">

            <!-- FORM -->
            <form id="formEditEkskul"
                  action="{{ route('extracurricular.update', $data->slug) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <!-- NAMA -->
                <div class="mb-4">
                    <label class="block text-sm mb-1 font-medium">
                        Nama Ekstrakurikuler
                    </label>
                    <input type="text"
                           id="firstInput" {{-- ✅ AUTO FOCUS TARGET --}}
                           name="name"
                           value="{{ old('name', $data->name) }}"
                           class="w-full border rounded-lg px-3 py-2"
                           required>
                </div>

                <!-- PEMBINA -->
                <div class="mb-4">
                    <label class="block text-sm mb-1 font-medium">
                        Pembina (Guru)
                    </label>

                    <select name="teacher_id"
                            class="w-full border rounded-lg px-3 py-2">

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
                    <label class="block text-sm mb-2 font-medium">
                        Anggota Siswa
                    </label>

                    <div class="max-h-60 overflow-y-auto border rounded-lg p-3 grid grid-cols-1 sm:grid-cols-2 gap-2">

                        @foreach($students as $student)
                            <label class="flex items-center gap-2 text-sm">

                                <input type="checkbox"
                                       name="student_ids[]"
                                       value="{{ $student->id }}"
                                       {{ in_array($student->id, $data->students->pluck('id')->toArray()) ? 'checked' : '' }}>

                                {{ $student->name }}

                            </label>
                        @endforeach

                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-between">

                    <a href="{{ route('extracurricular.index') }}"
                       class="px-4 py-2 rounded-lg bg-gray-200 text-sm">
                        Kembali
                    </a>

                    <button type="submit"
                            class="px-6 py-2 rounded-lg bg-blue-600 text-white text-sm">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ✅ AUTO FOCUS FIELD PERTAMA
    const firstInput = document.getElementById('firstInput');
    if (firstInput) {
        firstInput.focus();
    }

    // AJAX
    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    if (typeof window.updateData === 'function') {

        window.updateData('#formEditEkskul',
            "{{ route('extracurricular.update', $data->slug) }}",
            function (res) {

                $('#alertBox').html(`
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        ${res.message ?? 'Data berhasil diupdate'}
                    </div>
                `);

            }
        );

    } else {
        console.error('updateData belum tersedia (ajax.js belum ke-load)');
    }

});
</script>
@endpush