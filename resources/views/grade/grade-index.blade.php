@extends('layouts.app')

@section('content')

@php
$user = auth()->user();
@endphp

<div class="p-6 space-y-6">

    <!-- BREADCRUMB -->
    <div class="text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Manajemen Nilai
        </span>
    </div>


    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                {{ $user->role === 'student'
                    ? 'Nilai Saya'
                    : 'Manajemen Nilai' }}
            </h2>

            <p class="text-sm text-gray-500">
                {{ $user->role === 'student'
                    ? 'Rekap nilai pribadi'
                    : 'Kelola data nilai siswa' }}
            </p>
        </div>

        <div class="flex gap-2">

            @if(in_array($user->role, ['super_admin', 'admin']))
            <a href="{{ route('grades.archived') }}"
                class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">
                Archived
            </a>
            @endif

            @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
            <a href="{{ route('grades.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                + Tambah
            </a>
            @endif

        </div>
    </div>


    <!-- ALERT -->
    <div id="alertBox"></div>


    <!-- CARD -->
    <div class="grid md:grid-cols-3 gap-4">

        <div class="bg-white p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Total Data</p>
            <h3 class="text-2xl font-bold text-right">
                {{ number_format($data->count()) }}
            </h3>
        </div>

        <div class="bg-white p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Rata-rata Nilai</p>
            <h3 class="text-2xl font-bold text-right">
                {{
                    $data->count()
                    ? number_format(
                        $data->avg(function($item){
                            return (
                                ($item->assignment_score ?? 0) +
                                ($item->mid_exam_score ?? 0) +
                                ($item->final_exam_score ?? 0)
                            ) / 3;
                        }), 1)
                    : 0
                }}
            </h3>
        </div>

        <div class="bg-white p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Nilai Kosong</p>
            <h3 class="text-2xl text-red-500 font-bold text-right">
                {{
                    $data->filter(function($item){
                        return ($item->assignment_score ?? 0) == 0
                            && ($item->mid_exam_score ?? 0) == 0
                            && ($item->final_exam_score ?? 0) == 0;
                    })->count()
                }}
            </h3>
        </div>

    </div>


    <!-- FILTER -->
    @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
    <form method="GET"
        class="grid md:grid-cols-5 gap-3 bg-white p-4 border rounded-lg">

        <select name="academic_year"
            class="border p-2 rounded">
            <option value="">Tahun</option>

            @foreach($classes->unique('academic_year') as $c)
            <option value="{{ $c->academic_year }}"
                {{ request('academic_year') == $c->academic_year ? 'selected' : '' }}>
                {{ $c->academic_year }}
            </option>
            @endforeach
        </select>


        <select name="semester"
            class="border p-2 rounded">
            <option value="">Semester</option>

            <option value="Ganjil"
                {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>
                Ganjil
            </option>

            <option value="Genap"
                {{ request('semester') == 'Genap' ? 'selected' : '' }}>
                Genap
            </option>
        </select>


        <select name="major"
            class="border p-2 rounded">
            <option value="">Jurusan</option>

            @foreach($classes->unique('major') as $c)
                @if($c->major)
                <option value="{{ $c->major }}"
                    {{ request('major') == $c->major ? 'selected' : '' }}>
                    {{ $c->major }}
                </option>
                @endif
            @endforeach
        </select>


        <select name="class_id"
            class="border p-2 rounded">
            <option value="">Kelas</option>

            @foreach($classes as $c)
            <option value="{{ $c->id }}"
                {{ request('class_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
            @endforeach
        </select>


        <button class="bg-blue-600 text-white rounded px-3 py-2">
            Filter
        </button>

    </form>
    @endif


    <!-- TABLE -->
    <div class="bg-white rounded-lg border overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-center">Kelas</th>
                    <th class="p-3 text-center">Mapel</th>
                    <th class="p-3 text-right">Tugas</th>
                    <th class="p-3 text-right">UTS</th>
                    <th class="p-3 text-right">UAS</th>
                    <th class="p-3 text-right">Nilai</th>

                    @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
                    <th class="p-3 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>

                @forelse($data as $g)

                @php
                    $tugas = $g->assignment_score ?? 0;
                    $uts   = $g->mid_exam_score ?? 0;
                    $uas   = $g->final_exam_score ?? 0;
                    $final = ($tugas + $uts + $uas) / 3;
                @endphp

                <tr
                    id="row-{{ $g->id }}"
                    class="grade-row border-t hover:bg-gray-50 cursor-pointer"
                    data-url="{{ route('grades.show', $g->id) }}">

                    <td class="p-3">
                        {{ $g->student->name ?? '-' }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $g->student->class->name ?? '-' }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $g->schedule->subject->name ?? '-' }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($tugas) }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($uts) }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($uas) }}
                    </td>

                    <td class="p-3 text-right text-blue-600 font-bold">
                        {{ number_format($final, 1) }}
                    </td>

                    @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
                    <td class="p-3 text-center space-x-1">

                        <a href="{{ route('grades.edit', $g->id) }}"
                            onclick="event.stopPropagation()"
                            class="text-blue-600 text-xs hover:underline">
                            Edit
                        </a>

                        <form action="{{ route('grades.destroy', $g->id) }}"
                            method="POST"
                            class="inline formDelete"
                            data-id="{{ $g->id }}"
                            onclick="event.stopPropagation()">

                            @csrf
                            @method('DELETE')

                            <button class="text-red-600 text-xs hover:underline">
                                Archive
                            </button>

                        </form>

                    </td>
                    @endif

                </tr>

                @empty
                <tr>
                    <td colspan="8"
                        class="text-center py-6 text-gray-400">
                        Tidak ada data nilai
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
document.addEventListener('DOMContentLoaded', function() {

    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    // CLICK ROW
    $(document).on('click', '.grade-row', function() {
        let url = $(this).data('url');
        if (url) window.location.href = url;
    });

    // ARCHIVE
    $(document).on('submit', '.formDelete', function(e) {

        e.preventDefault();

        if (!confirm('Pindahkan ke archive?')) return;

        let url = this.action;
        let id = $(this).data('id');
        let row = $('#row-' + id);

        if (typeof deleteData === 'function') {

            deleteData(url, function(response) {

                row.remove();

                $('#alertBox').html(`
                    <div class="mb-4 bg-green-100 text-green-700 p-3 rounded-lg">
                        ${response.message ?? 'Data berhasil dipindahkan ke archive'}
                    </div>
                `);

            });

        } else {
            console.error('deleteData tidak ditemukan');
        }

    });

});
</script>
@endpush