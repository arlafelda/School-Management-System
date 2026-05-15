@extends('layouts.app')

@section('title', 'Arsip Siswa')

@section('content')

<div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

<!-- BREADCRUMB -->
<nav class="text-sm text-gray-500 mb-4">
    <ol class="flex items-center space-x-2">
        <li>Dashboard</li>
        <li>/</li>
        <li>Student</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Arsip</li>
    </ol>
</nav>

<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-bold">Arsip Siswa</h1>

    <a href="{{ route('students.index') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
        ← Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow border overflow-x-auto">

    <table class="w-full text-sm min-w-[800px]">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 text-left">Nama</th>
                <th class="p-4 text-center">NIS</th>
                <th class="p-4 text-center">Kelas</th>
                <th class="p-4 text-center">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($students as $student)
            <tr id="row-{{ $student->slug }}">

                <td class="p-4">
                    <p class="font-semibold">
                        {{ $student->name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $student->user->email ?? '-' }}
                    </p>
                </td>

                <td class="p-4 text-center">
                    {{ $student->nis }}
                </td>

                <td class="p-4 text-center">
                    {{ $student->class->name ?? '-' }}
                </td>

                <td class="p-4 text-center text-red-600 font-semibold">
                    Archived
                </td>

                <td class="p-4 text-center">

                    <form method="POST"
                        action="{{ route('students.restore', $student->slug) }}">
                        @csrf
                        @method('PUT')

                        <button type="submit"
                            onclick="return confirm('Restore data ini?')"
                            class="bg-green-600 text-white px-3 py-1 rounded">
                            Restore
                        </button>
                    </form>

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5"
                    class="p-6 text-center text-gray-500">
                    Tidak ada data arsip
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>

</div>

@endsection