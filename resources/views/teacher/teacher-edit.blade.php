@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

<div class="p-6">

    <div class="max-w-3xl mx-auto">

        <!-- 🔥 BREADCRUMB -->
        <nav class="text-sm text-gray-500 mb-4">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('teacher.index') }}" class="text-blue-600 hover:underline">
                        Guru
                    </a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{ route('teacher.show', $teacher->slug) }}" class="text-blue-600 hover:underline">
                        {{ $teacher->name }}
                    </a>
                </li>
                <li>/</li>
                <li class="text-gray-700 font-medium">
                    Edit
                </li>
            </ol>
        </nav>

        <div class="bg-white p-6 md:p-8 rounded-xl shadow border">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-blue-700">Edit Guru</h2>
            </div>

            <!-- FORM -->
            <form id="formEditGuru"
                  action="{{ route('teacher.update', $teacher->slug) }}"
                  method="POST"
                  class="space-y-5">

                @csrf
                @method('PUT')

                <!-- ✅ AUTO-FOCUS TARGET -->
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="name" id="firstInput"
                           value="{{ $teacher->name }}"
                           class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium">NIP</label>
                    <input type="text" name="nip" value="{{ $teacher->nip }}"
                           class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ $teacher->user->email ?? '' }}"
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

</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // 🔥 AUTO-FOCUS SAAT HALAMAN DIBUKA
    document.getElementById('firstInput')?.focus();

    if (typeof updateData !== 'undefined') {

        updateData('#formEditGuru', "{{ route('teacher.update', $teacher->slug) }}", {
            onSuccess: function () {

                // 🔥 AUTO-FOCUS ULANG (opsional)
                document.getElementById('firstInput')?.focus();
            }
        });

    } else {
        console.error('updateData belum tersedia');
    }

});
</script>
@endpush