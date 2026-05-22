@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- BREADCRUMB -->
    <div class="px-6 pt-4 text-sm text-gray-500">
        <a href="{{ route('class.index') }}" class="hover:text-blue-600">
            Kelas
        </a>
        <span class="mx-2">/</span>
        <span class="text-gray-700 font-medium">Tambah</span>
    </div>

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4">
        <h1 class="text-lg font-bold text-blue-700">Tambah Kelas</h1>
        <p class="text-sm text-gray-500">Input data kelas baru</p>
    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <!-- ALERT AJAX -->
        <div id="alertBox" class="max-w-3xl mx-auto mb-4"></div>

        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

            <!-- FORM -->
            <form id="formClass" action="{{ route('class.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Nama Kelas -->
                    <div>
                        <label class="text-sm font-medium">Nama Kelas</label>
                        <input
                            id="firstInput"
                            type="text"
                            name="name"
                            required
                            class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>

                    <!-- Tingkat -->
                    <div>
                        <label class="text-sm font-medium">Tingkat</label>
                        <select name="level"
                            class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="text-sm font-medium">Jurusan</label>
                        <input type="text" name="major"
                            class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="text-sm font-medium">Tahun Ajaran</label>
                        <input type="text" name="academic_year"
                            placeholder="2023/2024"
                            class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="text-sm font-medium">Semester</label>
                        <select name="semester"
                            class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <!-- Wali Kelas -->
                    <div>
                        <label class="text-sm font-medium">Wali Kelas</label>
                        <select name="teacher_id"
                            class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="mt-6 flex justify-end gap-2">

                    <a href="{{ route('class.index') }}"
                        class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                        Kembali
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

    // AUTO FOCUS AMAN
    setTimeout(() => {
        const first = document.getElementById('firstInput');
        if (first) first.focus();
    }, 100);

    // CEK AJAX HELPER
    if (typeof window.createData !== 'function') {
        console.error('ajax.js belum load');
        return;
    }

    // AJAX CREATE
    window.createData('#formClass', "{{ route('class.store') }}", {
        onSuccess: function (res) {

            $('#alertBox').html(`
                <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                    ${res.message ?? 'Data berhasil disimpan'}
                </div>
            `);

            const form = document.getElementById('formClass');
            form.reset();

            setTimeout(() => {
                const first = document.getElementById('firstInput');
                if (first) first.focus();
            }, 100);
        }
    });

});
</script>
@endpush