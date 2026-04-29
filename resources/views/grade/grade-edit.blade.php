@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Nilai Siswa</h1>
        <p class="text-sm text-gray-500">Perbarui nilai akademik siswa dengan benar</p>
    </div>

    <!-- CENTER WRAPPER (tidak pakai flex center lagi) -->
    <div class="bg-white rounded-xl shadow border max-w-3xl">

        <!-- FORM HEADER -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">
                Form Edit Nilai
            </h2>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('grades.update', $grade->id) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- SUBJECT -->
            <div>
                <label class="text-sm font-medium text-gray-600">Mata Pelajaran</label>

                <select name="subject"
                    class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <option value="Matematika" {{ $grade->subject == 'Matematika' ? 'selected' : '' }}>Matematika</option>
                    <option value="Bahasa Indonesia" {{ $grade->subject == 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                    <option value="Bahasa Inggris" {{ $grade->subject == 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris</option>
                    <option value="IPA" {{ $grade->subject == 'IPA' ? 'selected' : '' }}>IPA</option>
                    <option value="IPS" {{ $grade->subject == 'IPS' ? 'selected' : '' }}>IPS</option>

                </select>
            </div>

            <!-- NILAI -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="text-sm font-medium text-gray-600">Tugas</label>
                    <input type="number" name="assignment_score"
                        value="{{ $grade->assignment_score }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">UTS</label>
                    <input type="number" name="mid_exam_score"
                        value="{{ $grade->mid_exam_score }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">UAS</label>
                    <input type="number" name="final_exam_score"
                        value="{{ $grade->final_exam_score }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4 border-t">

                <a href="{{ route('grades.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800">
                    ← Kembali
                </a>

                <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm shadow">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection