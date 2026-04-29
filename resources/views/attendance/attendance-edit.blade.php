@extends('layouts.app')

@section('content')

<div class="p-8 max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold">Edit Absensi</h2>
        <p class="text-sm text-gray-500">Perbarui data absensi siswa</p>
    </div>

    <form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
        @csrf
        @method('PUT')

        <!-- FILTER / INFO -->
        <div class="grid md:grid-cols-3 gap-4 mb-6 bg-white p-4 rounded-lg border">

            <!-- KELAS -->
            <select name="class_id" class="border rounded-lg p-2 bg-gray-100" disabled>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}"
                        {{ $attendance->student->class_id == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <!-- MAPEL -->
            <select name="schedule_id" class="border rounded-lg p-2">
                @foreach($schedules as $s)
                    <option value="{{ $s->id }}"
                        {{ $attendance->schedule_id == $s->id ? 'selected' : '' }}>
                        {{ $s->teacher->subject ?? '-' }} ({{ $s->teacher->name ?? '-' }})
                    </option>
                @endforeach
            </select>

            <!-- TANGGAL -->
            <input type="date" name="date"
                value="{{ $attendance->date }}"
                class="border rounded-lg p-2">

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-lg shadow overflow-hidden">

            <div class="p-4 border-b">
                <h3 class="font-semibold">Data Siswa</h3>
            </div>

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">Nama</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-t">

                        <td class="p-3 font-medium">
                            {{ $attendance->student->name }}
                        </td>

                        <td class="p-3 text-center">

                            <div class="flex justify-center gap-4 flex-wrap">

                                <label>
                                    <input type="radio" name="status" value="hadir"
                                        {{ $attendance->status == 'hadir' ? 'checked' : '' }}>
                                    Hadir
                                </label>

                                <label>
                                    <input type="radio" name="status" value="izin"
                                        {{ $attendance->status == 'izin' ? 'checked' : '' }}>
                                    Izin
                                </label>

                                <label>
                                    <input type="radio" name="status" value="sakit"
                                        {{ $attendance->status == 'sakit' ? 'checked' : '' }}>
                                    Sakit
                                </label>

                                <label>
                                    <input type="radio" name="status" value="alpa"
                                        {{ $attendance->status == 'alpa' ? 'checked' : '' }}>
                                    Alpa
                                </label>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <!-- ACTION -->
        <div class="mt-6 flex justify-end gap-3">

            <a href="{{ route('attendance.index') }}"
                class="px-4 py-2 border rounded-lg text-sm">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Update
            </button>

        </div>

    </form>

</div>

@endsection