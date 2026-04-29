@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-lg font-bold text-blue-700">
                Tambah Kelas
            </h1>
            <p class="text-sm text-gray-500">
                Input data kelas baru
            </p>
        </div>

        <a href="{{ route('class.index') }}"
            class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>

    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <!-- ALERT -->
        <div id="alertBox" class="max-w-3xl mx-auto mb-4"></div>

        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

            <!-- ⛔ TAMBAH ID -->
            <form id="formClass" action="{{ route('class.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Nama Kelas -->
                    <div>
                        <label class="text-sm font-medium">Nama Kelas</label>
                        <input type="text" name="name" required
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
                                {{ $teacher->name }} - {{ $teacher->subject }}
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
    document.addEventListener('DOMContentLoaded', function() {

        // tunggu sampai jQuery benar-benar ada
        if (typeof window.$ === 'undefined') {
            console.error('jQuery belum load');
            return;
        }

        // tunggu sampai function ajax ada
        if (typeof window.createData === 'function') {

            window.createData('#formClass', "{{ route('class.store') }}", function(res) {

                $('#alertBox').html(`
                <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                    ${res.message ?? 'Data kelas berhasil ditambahkan'}
                </div>
            `);

            });

        } else {
            console.error('createData belum tersedia (ajax.js belum ke-load)');
        }

    });
</script>
@endpush