@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<div class="space-y-6">

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- ERROR VALIDATION (fallback Laravel non-AJAX) -->
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

                    <input type="email" name="email" placeholder="Email Login"
                        class="border rounded-lg px-3 py-2 text-sm" required>

                    <input type="password" name="password" placeholder="Password"
                        class="border rounded-lg px-3 py-2 text-sm" required>

                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                        class="border rounded-lg px-3 py-2 text-sm col-span-2" required>

                </div>
            </div>

            <!-- DATA SISWA -->
            <div class="border-b pb-4">
                <h2 class="font-semibold mb-3">Data Siswa</h2>

                <div class="grid grid-cols-2 gap-4">

                    <input type="text" name="name" placeholder="Nama Siswa"
                        class="border rounded-lg px-3 py-2 text-sm" required>

                    <input type="text" name="nisn" placeholder="NISN"
                        class="border rounded-lg px-3 py-2 text-sm" required>

                    <input type="text" name="nis" placeholder="NIS"
                        class="border rounded-lg px-3 py-2 text-sm" required>

                    <select name="class_id" class="border rounded-lg px-3 py-2 text-sm" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }} - {{ $class->major }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="major" placeholder="Jurusan"
                        class="border rounded-lg px-3 py-2 text-sm">

                    <select name="gender" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>

                    <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>

                </div>

                <input type="text" name="birth_place" placeholder="Tempat Lahir"
                    class="border rounded-lg px-3 py-2 text-sm mt-4 w-full">

                <input type="date" name="birth_date"
                    class="border rounded-lg px-3 py-2 text-sm mt-4 w-full">

                <textarea name="address" placeholder="Alamat"
                    class="w-full border rounded-lg px-3 py-2 text-sm mt-4"></textarea>

                <input type="text" name="phone" placeholder="No HP"
                    class="w-full border rounded-lg px-3 py-2 text-sm mt-4">

            </div>

            <!-- ORANG TUA -->
            <div>
                <h2 class="font-semibold mb-3">Data Orang Tua</h2>

                <div class="grid grid-cols-2 gap-4">

                    <input type="text" name="father_name" placeholder="Nama Ayah"
                        class="border rounded-lg px-3 py-2 text-sm">

                    <input type="text" name="mother_name" placeholder="Nama Ibu"
                        class="border rounded-lg px-3 py-2 text-sm">

                    <input type="text" name="parent_phone" placeholder="No HP Orang Tua"
                        class="border rounded-lg px-3 py-2 text-sm">

                    <textarea name="parent_address" placeholder="Alamat Orang Tua"
                        class="border rounded-lg px-3 py-2 text-sm"></textarea>

                </div>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 pt-4">

                <a href="{{ route('students.index') }}"
                    class="px-4 py-2 border rounded-lg text-sm">
                    Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>
    // pakai AJAX PRO kamu (ajax.js)
    createData('#formSiswa', "{{ route('students.store') }}");
</script>
@endpush