@extends('layouts.app')

@section('title', 'Tambah Nilai Kolektif')

@section('content')

<div class="p-6 space-y-6 bg-gray-100 min-h-screen">

    <!-- HEADER -->
    <div class="bg-white border rounded-lg p-4 flex justify-between items-center">

        <div>
            <h1 class="text-xl font-bold text-blue-700">
                Tambah Nilai Kolektif
            </h1>
            <p class="text-sm text-gray-500">
                Input nilai siswa secara massal
            </p>
        </div>

        <input type="text"
               placeholder="Cari siswa..."
               class="w-64 px-3 py-2 border rounded-lg text-sm">

    </div>

    <!-- FILTER -->
    <form method="GET" id="filterForm"
          class="grid md:grid-cols-5 gap-3 bg-white p-4 border rounded-lg">

        <select name="academic_year" onchange="filter()"
                class="border rounded px-3 py-2 text-sm">
            <option value="">Tahun Ajaran</option>
            @foreach($classes->unique('academic_year') as $c)
                <option value="{{ $c->academic_year }}"
                    {{ request('academic_year') == $c->academic_year ? 'selected' : '' }}>
                    {{ $c->academic_year }}
                </option>
            @endforeach
        </select>

        <select name="semester" onchange="filter()"
                class="border rounded px-3 py-2 text-sm">
            <option value="">Semester</option>
            <option value="Ganjil" {{ request('semester')=='Ganjil'?'selected':'' }}>Ganjil</option>
            <option value="Genap" {{ request('semester')=='Genap'?'selected':'' }}>Genap</option>
        </select>

        <select name="major" onchange="filter()"
                class="border rounded px-3 py-2 text-sm">
            <option value="">Jurusan</option>
            @foreach($classes->unique('major') as $c)
                <option value="{{ $c->major }}"
                    {{ request('major') == $c->major ? 'selected' : '' }}>
                    {{ $c->major }}
                </option>
            @endforeach
        </select>

        <select name="class_id" onchange="filter()"
                class="border rounded px-3 py-2 text-sm">
            <option value="">Kelas</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}"
                    {{ request('class_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <select name="teacher_id" onchange="filter()"
                class="border rounded px-3 py-2 text-sm">
            <option value="">Mapel</option>
            @foreach($teachers as $t)
                <option value="{{ $t->id }}"
                    {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->name }} - {{ $t->subject }}
                </option>
            @endforeach
        </select>

    </form>

    <!-- FORM NILAI -->
    <form method="POST" action="{{ route('grades.store') }}">
        @csrf

        <input type="hidden" name="subject"
               value="{{ optional($teachers->where('id', request('teacher_id'))->first())->subject }}">

        <!-- TABLE -->
        <div class="bg-white border rounded-lg overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">NISN</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Tugas</th>
                        <th class="p-3">UTS</th>
                        <th class="p-3">UAS</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($students as $i => $student)
                    <tr class="border-t">

                        <td class="p-3 text-center">
                            {{ $i+1 }}
                            <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                        </td>

                        <td class="p-3 text-center">{{ $student->nisn }}</td>
                        <td class="p-3">{{ $student->name }}</td>

                        <td class="p-3">
                            <input type="number" name="assignment_score[]"
                                   class="w-full border rounded px-2 py-1 text-center">
                        </td>

                        <td class="p-3">
                            <input type="number" name="mid_exam_score[]"
                                   class="w-full border rounded px-2 py-1 text-center">
                        </td>

                        <td class="p-3">
                            <input type="number" name="final_exam_score[]"
                                   class="w-full border rounded px-2 py-1 text-center">
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-400">
                            Tidak ada siswa
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end mt-6 gap-3">

            <a href="{{ route('grades.index') }}"
               class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                Batal
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                Simpan Nilai
            </button>

        </div>

    </form>

</div>

<script>
function filter(){
    document.getElementById('filterForm').submit();
}
</script>

@endsection