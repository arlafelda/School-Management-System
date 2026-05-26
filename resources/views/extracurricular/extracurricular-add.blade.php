@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- BREADCRUMB -->
    <div class="px-6 pt-4 text-sm text-gray-500">
        <a href="{{ route('extracurricular.index') }}"
           class="hover:text-blue-600">
            Ekstrakurikuler
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Tambah Data
        </span>
    </div>

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h2 class="font-semibold text-blue-700 text-xl">
                Tambah Ekstrakurikuler
            </h2>

            <p class="text-sm text-gray-500">
                Input data kegiatan ekstrakurikuler
            </p>
        </div>

        <a href="{{ route('extracurricular.index') }}"
           class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>

    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <!-- ALERT -->
        <div id="alertBox" class="max-w-3xl mx-auto mb-4"></div>

        <div class="bg-white p-6 rounded-lg shadow max-w-3xl mx-auto">

            <!-- FORM -->
            <form id="formEkskul"
                  method="POST"
                  action="{{ route('extracurricular.store') }}">

                @csrf

                <!-- NAMA EKSKUL -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">
                        Nama Ekstrakurikuler
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="firstInput"
                        type="text"
                        name="name"
                        required
                        autofocus
                        placeholder="Masukkan nama ekstrakurikuler"
                        class="w-full border rounded-lg p-2 focus:outline-none focus:ring">
                </div>

                <!-- PEMBINA -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">
                        Pembina
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="teacher_id"
                        required
                        class="w-full border rounded-lg p-2 focus:outline-none focus:ring">

                        <option value="">
                            -- Pilih Guru --
                        </option>

                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- SISWA (OPSIONAL) -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium">
                        Pilih Siswa
                        <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>

                    <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border p-3 rounded bg-gray-50">

                        @foreach($students as $s)
                            <label class="flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    name="student_ids[]"
                                    value="{{ $s->id }}">
                                {{ $s->name }}
                            </label>
                        @endforeach

                    </div>

                    <small class="text-gray-500">
                        Boleh dikosongkan jika belum ingin menambahkan siswa
                    </small>
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


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // AUTO FOCUS
    setTimeout(() => {
        let first = document.getElementById('firstInput');
        if (first) {
            first.focus();
        }
    }, 100);

    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    if (typeof window.createData === 'function') {

        window.createData(
            '#formEkskul',
            "{{ route('extracurricular.store') }}",
            {
                onSuccess: function(res) {

                    $('#alertBox').html(`
                        <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                            ${res.message ?? 'Data berhasil ditambahkan'}
                        </div>
                    `);

                    document.getElementById('formEkskul').reset();
                    document.getElementById('firstInput').focus();
                }
            }
        );

    } else {
        console.error('createData belum tersedia (ajax.js belum ke-load)');
    }

});
</script>
@endpush