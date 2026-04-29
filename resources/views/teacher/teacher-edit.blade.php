@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

<div class="p-6">

    <div class="max-w-3xl mx-auto bg-white p-6 md:p-8 rounded-xl shadow border">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-blue-700">Edit Guru</h2>

            <a href="{{ route('teacher.index') }}"
               class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
                ← Kembali
            </a>
        </div>

        <!-- FORM -->
        <form id="formEditGuru"
              action="{{ route('teacher.update', $teacher->id) }}"
              method="POST"
              class="space-y-5">

            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text" name="name" value="{{ $teacher->name }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm font-medium">NIP</label>
                <input type="text" name="nip" value="{{ $teacher->nip }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ $teacher->email }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm font-medium">Mapel</label>
                <input type="text" name="subject" value="{{ $teacher->subject }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm font-medium">No HP</label>
                <input type="text" name="phone" value="{{ $teacher->phone }}"
                       class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm font-medium">Jabatan</label>
                <select name="position" class="w-full border p-2 rounded">
                    <option value="guru" {{ $teacher->position == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="wali_kelas" {{ $teacher->position == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('teacher.index') }}"
                   class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded">
                    Update
                </button>
            </div>

        </form>

        <div id="alertBox" class="mt-4"></div>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    if (typeof updateData !== 'undefined') {

        updateData('#formEditGuru', "{{ route('teacher.update', $teacher->id) }}");

    } else {
        console.error('updateData belum tersedia');
    }

});
</script>
@endpush