@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')

<!-- BREADCRUMB -->
<nav class="text-sm text-gray-500 mb-4">
    <ol class="list-reset flex items-center space-x-2">
        <li>
            <a href="{{ route('teacher.index') }}" class="text-blue-600 hover:underline">
                Guru
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-700 font-medium">
            Tambah Guru
        </li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white p-6 md:p-8 rounded-xl shadow border">

    <!-- TITLE -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Form Tambah Guru</h1>
        <p class="text-gray-500 text-sm">Isi data guru dengan lengkap</p>
    </div>

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- ERROR -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <form id="formGuru" class="space-y-5">
        @csrf

        <!-- NAMA -->
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" id="firstInput" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- NIP -->
        <div>
            <label class="block text-sm font-medium mb-1">NIP</label>
            <input type="text" name="nip" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- SUBJECT (DYNAMIC MULTI INPUT) -->
        <div>
            <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>

            <div id="subject-wrapper" class="space-y-2">

                <div class="flex gap-2 subject-row">
                    <select name="subject_ids[]"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

                        <option value="">Pilih Mata Pelajaran</option>

                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">
                                {{ $subject->name }}
                            </option>
                        @endforeach

                    </select>

                    <button type="button"
                        class="remove-subject hidden px-3 bg-red-500 text-white rounded">
                        X
                    </button>
                </div>

            </div>

            <button type="button" id="add-subject"
                class="mt-2 text-sm text-blue-600 hover:underline">
                + Tambah Mata Pelajaran
            </button>
        </div>

        <!-- PHONE -->
        <div>
            <label class="block text-sm font-medium mb-1">No. HP</label>
            <input type="text" name="phone"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- POSITION -->
        <div>
            <label class="block text-sm font-medium mb-1">Jabatan</label>
            <select name="position"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

                <option value="">Pilih Jabatan</option>
                <option value="guru">Guru</option>
                <option value="wali_kelas">Wali Kelas</option>

            </select>
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('teacher.index') }}"
                class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                Kembali
            </a>

            <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Simpan
            </button>
        </div>

    </form>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const firstInput = document.getElementById('firstInput');
    const wrapper = document.getElementById('subject-wrapper');
    const addBtn = document.getElementById('add-subject');

    if (firstInput) firstInput.focus();

    // CREATE ROW SUBJECT
    function createRow() {
        const row = document.createElement('div');
        row.className = "flex gap-2 subject-row";

        row.innerHTML = `
            <select name="subject_ids[]"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

                <option value="">Pilih Mata Pelajaran</option>

                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach

            </select>

            <button type="button"
                class="remove-subject px-3 bg-red-500 text-white rounded">
                X
            </button>
        `;

        wrapper.appendChild(row);
    }

    // ADD SUBJECT
    addBtn.addEventListener('click', function () {
        createRow();
    });

    // REMOVE SUBJECT
    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-subject')) {
            e.target.closest('.subject-row').remove();
        }
    });

    // AJAX SUBMIT
    if (typeof createData !== 'undefined') {

        createData('#formGuru', "{{ route('teacher.store') }}", {
            onSuccess: function () {
                document.getElementById('formGuru').reset();

                // reset hanya 1 row saja
                wrapper.innerHTML = `
                    <div class="flex gap-2 subject-row">
                        <select name="subject_ids[]"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

                            <option value="">Pilih Mata Pelajaran</option>

                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach

                        </select>

                        <button type="button"
                            class="remove-subject hidden px-3 bg-red-500 text-white rounded">
                            X
                        </button>
                    </div>
                `;

                firstInput?.focus();
            }
        });

    } else {
        console.error('createData belum tersedia');
    }

});
</script>
@endpush