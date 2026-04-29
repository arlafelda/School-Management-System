@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow mt-6">

    <h1 class="text-xl font-bold mb-6">Edit Kelas</h1>

    <form action="{{ route('class.update', $class->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">

            <!-- Nama -->
            <div>
                <label class="text-sm">Nama Kelas</label>
                <input type="text" name="name"
                    value="{{ $class->name }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Tingkat -->
            <div>
                <label class="text-sm">Tingkat</label>
                <select name="level" class="w-full border rounded-lg px-3 py-2">
                    <option value="10" {{ $class->level == 10 ? 'selected' : '' }}>10</option>
                    <option value="11" {{ $class->level == 11 ? 'selected' : '' }}>11</option>
                    <option value="12" {{ $class->level == 12 ? 'selected' : '' }}>12</option>
                </select>
            </div>

            <!-- Jurusan -->
            <div>
                <label class="text-sm">Jurusan</label>
                <input type="text" name="major"
                    value="{{ $class->major }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Tahun Ajaran -->
            <div>
                <label class="text-sm">Tahun Ajaran</label>
                <input type="text" name="academic_year"
                    value="{{ $class->academic_year }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Semester -->
            <div>
                <label class="text-sm">Semester</label>
                <select name="semester" class="w-full border rounded-lg px-3 py-2">
                    <option value="Ganjil" {{ $class->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ $class->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>

            <!-- Wali Kelas -->
            <div>
                <label class="text-sm">Wali Kelas</label>
                <select name="teacher_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih Guru --</option>

                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-6 flex justify-end gap-2">

            <a href="{{ route('class.index') }}"
                class="px-4 py-2 border rounded-lg">
                Kembali
            </a>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Update
            </button>

        </div>

    </form>

</div>

@endsection