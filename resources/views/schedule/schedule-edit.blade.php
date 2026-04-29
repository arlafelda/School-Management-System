@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')

<div class="space-y-6">

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-bold">Edit Jadwal</h1>
        <p class="text-gray-500 text-sm">Perbarui data jadwal pelajaran</p>
    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-lg shadow max-w-xl">

        <form id="formEditSchedule"
              action="{{ route('schedule.update', $schedule->id) }}"
              method="POST"
              class="space-y-4">

            @csrf
            @method('PUT')

            <!-- KELAS -->
            <div>
                <label class="block text-sm font-medium mb-1">Kelas</label>
                <select name="class_id" required class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $class->major }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- HARI -->
            <div>
                <label class="block text-sm font-medium mb-1">Hari</label>
                <select name="day" required class="w-full border rounded px-3 py-2 text-sm">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                        <option value="{{ $day }}"
                            {{ old('day', $schedule->day) == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- TANGGAL -->
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="date"
                    value="{{ old('date', optional($schedule)->date ? \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') : '') }}"
                    class="w-full border rounded px-3 py-2 text-sm"
                    required>
            </div>

            <!-- JAM -->
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                    <input type="time" name="start_time"
                        value="{{ old('start_time', $schedule->start_time) }}"
                        class="w-full border rounded px-3 py-2 text-sm"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jam Selesai</label>
                    <input type="time" name="end_time"
                        value="{{ old('end_time', $schedule->end_time) }}"
                        class="w-full border rounded px-3 py-2 text-sm"
                        required>
                </div>

            </div>

            <!-- GURU -->
            <div>
                <label class="block text-sm font-medium mb-1">Guru</label>
                <select name="teacher_id" required class="w-full border rounded px-3 py-2 text-sm">

                    <option value="">-- Pilih Guru --</option>

                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} - {{ $teacher->subject }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between pt-2">

                <a href="{{ route('schedule.index') }}"
                   class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                    Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
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

    if (typeof updateData === "function") {

        updateData('#formEditSchedule',
            "{{ route('schedule.update', $schedule->id) }}",
            function (res) {

                document.getElementById('alertBox').innerHTML = `
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        ${res.message ?? 'Data berhasil diupdate'}
                    </div>
                `;
            }
        );

    } else {
        console.error("updateData belum tersedia");
    }

});
</script>
@endpush