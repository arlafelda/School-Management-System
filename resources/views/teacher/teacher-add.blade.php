@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 md:p-8 rounded-xl shadow border">

    <!-- TITLE -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Form Tambah Guru</h1>
        <p class="text-gray-500 text-sm">Isi data guru dengan lengkap</p>
    </div>

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- ERROR VALIDATION (fallback Laravel) -->
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

        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">NIP</label>
            <input type="text" name="nip" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>
            <input type="text" name="subject"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">No. HP</label>
            <input type="text" name="phone"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jabatan</label>
            <select name="position" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">Pilih Jabatan</option>
                <option value="guru">Guru</option>
                <option value="wali_kelas">Wali Kelas</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

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
    // AJAX PAKAI ajax.js (GLOBAL FUNCTION)
    createData('#formGuru', "{{ route('teacher.store') }}");
</script>
@endpush