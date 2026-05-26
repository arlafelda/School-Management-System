@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li class="text-gray-700 font-medium">Dashboard</li>
            <li>/</li>
            <li class="text-gray-700 font-medium">Siswa</li>
            <li>/</li>
            <li class="text-gray-500">Tambah</li>
        </ol>
    </nav>

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- ERROR VALIDATION -->
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- TITLE -->
    <div>
        <h1 class="text-2xl font-bold">Form Tambah Siswa</h1>
        <p class="text-gray-500 text-sm">Isi data siswa dengan lengkap</p>
    </div>

    <!-- FORM -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow border">

        <form id="formSiswa" class="space-y-6">

            @csrf

            <!-- LOGIN -->
            <div class="border-b pb-4">
                <h2 class="font-semibold mb-3">Akun Login</h2>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium">
                            Email Login
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="firstInput"
                            placeholder="Masukkan email login"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Password
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-medium">
                            Konfirmasi Password
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                    </div>

                </div>
            </div>

            <!-- DATA SISWA -->
            <div class="border-b pb-4">
                <h2 class="font-semibold mb-3">Data Siswa</h2>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium">
                            Nama Siswa
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            placeholder="Masukkan nama siswa"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            NISN
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nisn"
                            placeholder="Masukkan NISN"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            NIS
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nis"
                            placeholder="Masukkan NIS"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Kelas
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="class_id"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->name }} - {{ $class->major }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Jurusan
                        </label>
                        <input
                            type="text"
                            name="major"
                            placeholder="Masukkan jurusan"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1">
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Jenis Kelamin
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="gender"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Status
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"
                            required>
                            <option value="">Pilih status</option>
                            <option value="aktif">Aktif</option>
                            <option value="lulus">Lulus</option>
                            <option value="pindah">Pindah</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium">
                        Tempat Lahir
                    </label>
                    <input
                        type="text"
                        name="birth_place"
                        placeholder="Masukkan tempat lahir"
                        class="border rounded-lg px-3 py-2 text-sm w-full mt-1">
                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium">
                        Tanggal Lahir
                    </label>
                    <input
                        type="date"
                        name="birth_date"
                        class="border rounded-lg px-3 py-2 text-sm w-full mt-1">
                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium">
                        Alamat
                    </label>
                    <textarea
                        name="address"
                        placeholder="Masukkan alamat lengkap"
                        class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium">
                        No HP
                    </label>
                    <input
                        type="text"
                        name="phone"
                        placeholder="Masukkan nomor HP"
                        class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>

            </div>

            <!-- ORANG TUA -->
            <div>
                <h2 class="font-semibold mb-3">Data Orang Tua</h2>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium">
                            Nama Ayah
                        </label>
                        <input
                            type="text"
                            name="father_name"
                            placeholder="Masukkan nama ayah"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1">
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Nama Ibu
                        </label>
                        <input
                            type="text"
                            name="mother_name"
                            placeholder="Masukkan nama ibu"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1">
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            No HP Orang Tua
                        </label>
                        <input
                            type="text"
                            name="parent_phone"
                            placeholder="Masukkan nomor HP orang tua"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1">
                    </div>

                    <div>
                        <label class="text-sm font-medium">
                            Alamat Orang Tua
                        </label>
                        <textarea
                            name="parent_address"
                            placeholder="Masukkan alamat orang tua"
                            class="border rounded-lg px-3 py-2 text-sm w-full mt-1"></textarea>
                    </div>

                </div>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 pt-4">

                <a href="{{ route('students.index') }}"
                   class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                    Kembali
                </a>

                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById('firstInput');

    if (input) input.focus();

    if (typeof createData !== 'undefined') {

        createData('#formSiswa', "{{ route('students.store') }}", {
            onSuccess: function () {

                document.getElementById('formSiswa').reset();

                if (input) input.focus();
            }
        });

    } else {
        console.error('createData belum tersedia');
    }

});
</script>
@endpush