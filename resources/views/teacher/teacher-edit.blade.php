@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

<div class="p-6">

    <div class="max-w-3xl mx-auto">

        <!-- HEADER -->
        <div class="mb-4">
            <h2 class="text-xl font-bold text-blue-700">Edit Guru</h2>
        </div>

        <!-- FORM -->
        <form id="formEditGuru"
              action="{{ route('teacher.update', $teacher->slug) }}"
              method="POST"
              class="space-y-5">

            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $teacher->name) }}"
                       class="w-full border p-2 rounded">
            </div>

            <!-- NIP -->
            <div>
                <label class="text-sm font-medium">NIP</label>
                <input type="text"
                       name="nip"
                       value="{{ old('nip', $teacher->nip) }}"
                       class="w-full border p-2 rounded">
            </div>

            <!-- EMAIL (READONLY) -->
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email"
                       value="{{ $teacher->user->email ?? '-' }}"
                       disabled
                       class="w-full border p-2 rounded bg-gray-100">
            </div>

            <!-- PASSWORD (NEW ADDITION) -->
            <div>
                <label class="text-sm font-medium">Password Baru (opsional)</label>
                <input type="password"
                       name="password"
                       placeholder="Kosongkan jika tidak ingin mengubah password"
                       class="w-full border p-2 rounded">
            </div>

            <!-- SUBJECT REPEATER -->
            <div>
                <label class="text-sm font-medium">Mata Pelajaran</label>

                <div id="subject-wrapper" class="space-y-2">

                    @foreach($teacher->subjects as $subject)
                        <div class="flex gap-2 subject-row">

                            <select name="subject_ids[]" class="w-full border p-2 rounded">

                                <option value="">Pilih Mata Pelajaran</option>

                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}"
                                        {{ $subject->id == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach

                            </select>

                            <button type="button"
                                    class="remove-subject px-3 bg-red-500 text-white rounded">
                                X
                            </button>

                        </div>
                    @endforeach

                    @if($teacher->subjects->count() == 0)
                        <div class="flex gap-2 subject-row">

                            <select name="subject_ids[]" class="w-full border p-2 rounded">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>

                            <button type="button"
                                    class="remove-subject hidden px-3 bg-red-500 text-white rounded">
                                X
                            </button>

                        </div>
                    @endif

                </div>

                <!-- ADD SUBJECT -->
                <button type="button"
                        id="add-subject"
                        class="mt-2 px-3 py-1 bg-green-600 text-white rounded text-sm">
                    + Tambah Mata Pelajaran
                </button>

            </div>

            <!-- PHONE -->
            <div>
                <label class="text-sm font-medium">No HP</label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone', $teacher->phone) }}"
                       class="w-full border p-2 rounded">
            </div>

            <!-- POSITION -->
            <div>
                <label class="text-sm font-medium">Jabatan</label>

                <select name="position" class="w-full border p-2 rounded">

                    <option value="guru"
                        {{ $teacher->position == 'guru' ? 'selected' : '' }}>
                        Guru
                    </option>

                    <option value="wali_kelas"
                        {{ $teacher->position == 'wali_kelas' ? 'selected' : '' }}>
                        Wali Kelas
                    </option>

                </select>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('teacher.index') }}"
                   class="px-5 py-2 border rounded-lg text-sm hover:bg-gray-100">
                    ← Kembali
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
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

    const wrapper = document.getElementById('subject-wrapper');
    const addBtn = document.getElementById('add-subject');

    // ➕ tambah mapel
    addBtn.addEventListener('click', function () {

        const row = document.createElement('div');
        row.classList.add('flex', 'gap-2', 'subject-row');

        row.innerHTML = `
            <select name="subject_ids[]" class="w-full border p-2 rounded">
                <option value="">Pilih Mata Pelajaran</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <button type="button" class="remove-subject px-3 bg-red-500 text-white rounded">
                X
            </button>
        `;

        wrapper.appendChild(row);
    });

    // ❌ hapus mapel
    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-subject')) {
            e.target.closest('.subject-row').remove();
        }
    });

});
</script>
@endpush