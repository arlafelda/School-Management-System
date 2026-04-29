@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="space-y-6">

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- ERROR VALIDATION (fallback Laravel) -->
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- TITLE -->
    <div>
        <h1 class="text-2xl font-bold">Form Edit Siswa</h1>
        <p class="text-gray-500 text-sm">Perbarui data siswa</p>
    </div>

    <!-- FORM -->
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow border">

        <form id="formEditSiswa" class="space-y-4">

            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $student->id }}">

            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email"
                    value="{{ $student->user->email ?? '' }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Password (Opsional)</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Nama Siswa</label>
                <input type="text" name="name"
                    value="{{ $student->name }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">NISN</label>
                <input type="text" name="nisn"
                    value="{{ $student->nisn }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">NIS</label>
                <input type="text" name="nis"
                    value="{{ $student->nis }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Kelas</label>
                <select name="class_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm">

                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ $student->class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $class->major }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Jurusan</label>
                <input type="text" name="major"
                    value="{{ $student->major }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Jenis Kelamin</label>
                <select name="gender"
                    class="w-full border rounded-lg px-3 py-2 text-sm">

                    <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>

                    <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>

                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Tempat Lahir</label>
                <input type="text" name="birth_place"
                    value="{{ $student->birth_place }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Lahir</label>
                <input type="date" name="birth_date"
                    value="{{ $student->birth_date }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Alamat</label>
                <textarea name="address"
                    class="w-full border rounded-lg px-3 py-2 text-sm">{{ $student->address }}</textarea>
            </div>

            <div>
                <label class="text-sm font-medium">No HP</label>
                <input type="text" name="phone"
                    value="{{ $student->phone }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status"
                    class="w-full border rounded-lg px-3 py-2 text-sm">

                    <option value="aktif" {{ $student->status == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="lulus" {{ $student->status == 'lulus' ? 'selected' : '' }}>
                        Lulus
                    </option>

                    <option value="pindah" {{ $student->status == 'pindah' ? 'selected' : '' }}>
                        Pindah
                    </option>

                </select>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 pt-4">

                <a href="{{ route('students.index') }}"
                    class="px-4 py-2 border rounded-lg text-sm">
                    Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm">
                    Update
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    if (typeof updateData !== 'undefined') {

        updateData('#formEditSiswa', "{{ route('students.update', $student->id) }}");

    } else {
        console.error('updateData belum tersedia');
    }

});
</script>
@endpush