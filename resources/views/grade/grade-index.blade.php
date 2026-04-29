@extends('layouts.app')

@section('content')

<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Nilai</h2>
            <p class="text-sm text-gray-500">Kelola data nilai siswa</p>
        </div>

        <a href="{{ route('grades.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            + Tambah
        </a>
    </div>

    <!-- CARD -->
    <div class="grid md:grid-cols-3 gap-4">

        <div class="bg-white p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Total Data</p>
            <h3 class="text-2xl font-bold">{{ $data->count() }}</h3>
        </div>

        <div class="bg-white p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Rata-rata Nilai</p>
            <h3 class="text-2xl font-bold">
                {{
                    $data->count()
                    ? number_format(
                        $data->avg(function($item){
                            return ($item->assignment_score + $item->mid_exam_score + $item->final_exam_score) / 3;
                        }), 1)
                    : 0
                }}
            </h3>
        </div>

        <div class="bg-white p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Nilai Kosong</p>
            <h3 class="text-2xl text-red-500 font-bold">
                {{
                    $data->filter(function($item){
                        return $item->assignment_score == 0
                            && $item->mid_exam_score == 0
                            && $item->final_exam_score == 0;
                    })->count()
                }}
            </h3>
        </div>

    </div>

    <!-- FILTER -->
    <form method="GET" class="grid md:grid-cols-5 gap-3 bg-white p-4 border rounded-lg">

        <select name="academic_year" class="border p-2 rounded">
            <option value="">Tahun</option>
            @foreach($classes->unique('academic_year') as $c)
                <option value="{{ $c->academic_year }}">
                    {{ $c->academic_year }}
                </option>
            @endforeach
        </select>

        <select name="semester" class="border p-2 rounded">
            <option value="">Semester</option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
        </select>

        <select name="major" class="border p-2 rounded">
            <option value="">Jurusan</option>
            @foreach($classes->unique('major') as $c)
                <option value="{{ $c->major }}">
                    {{ $c->major }}
                </option>
            @endforeach
        </select>

        <select name="class_id" class="border p-2 rounded">
            <option value="">Kelas</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}">
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <button class="bg-blue-600 text-white rounded px-3 py-2">
            Filter
        </button>

    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-lg border overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-center">Kelas</th>
                    <th class="p-3 text-center">Mapel</th>
                    <th class="p-3 text-center">Tugas</th>
                    <th class="p-3 text-center">UTS</th>
                    <th class="p-3 text-center">UAS</th>
                    <th class="p-3 text-center">Nilai</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $g)

                @php
                    $final = ($g->assignment_score + $g->mid_exam_score + $g->final_exam_score) / 3;
                @endphp

                <!-- ROW BISA DIKLIK -->
                <tr data-url="{{ route('grades.show', $g->id) }}"
                    class="grade-row border-t hover:bg-gray-50 cursor-pointer">

                    <td class="p-3">{{ $g->student->name ?? '-' }}</td>

                    <td class="p-3 text-center">
                        {{ $g->student->class->name ?? '-' }}
                    </td>

                    <td class="p-3 text-center">{{ $g->subject }}</td>

                    <td class="p-3 text-center">{{ $g->assignment_score }}</td>
                    <td class="p-3 text-center">{{ $g->mid_exam_score }}</td>
                    <td class="p-3 text-center">{{ $g->final_exam_score }}</td>

                    <td class="p-3 text-center text-blue-600 font-bold">
                        {{ number_format($final,1) }}
                    </td>

                    <td class="p-3 text-center space-x-1">

                        <!-- EDIT -->
                        <a href="{{ route('grades.edit', $g->id) }}"
                           onclick="event.stopPropagation()"
                           class="text-blue-600 text-xs hover:underline">
                            Edit
                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('grades.destroy', $g->id) }}"
                            method="POST"
                            class="inline"
                            onclick="event.stopPropagation()">

                            @csrf
                            @method('DELETE')

                            <button class="text-red-600 text-xs hover:underline"
                                onclick="return confirm('Hapus data?')">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    // CLICK ROW → DETAIL
    $('.grade-row').on('click', function () {
        let url = $(this).data('url');
        window.location.href = url;
    });

});
</script>
@endpush