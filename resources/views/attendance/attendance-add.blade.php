@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">Input Absensi</h2>

        <span class="text-sm text-gray-500">
            Tanggal: {{ $date ?? date('Y-m-d') }}
        </span>
    </div>

    <main>

        <!-- ================= FILTER ================= -->
        <form method="GET" action="{{ route('attendance.create') }}"
              class="bg-white p-4 rounded-xl shadow-sm mb-6 grid md:grid-cols-6 gap-4">

            <select name="academic_year" class="border rounded-lg px-3 py-2">
                <option value="">Tahun Ajaran</option>
                @foreach($classes->unique('academic_year') as $c)
                    <option value="{{ $c->academic_year }}"
                        {{ request('academic_year') == $c->academic_year ? 'selected' : '' }}>
                        {{ $c->academic_year }}
                    </option>
                @endforeach
            </select>

            <select name="semester" class="border rounded-lg px-3 py-2">
                <option value="">Semester</option>
                <option value="Ganjil" {{ request('semester')=='Ganjil'?'selected':'' }}>Ganjil</option>
                <option value="Genap" {{ request('semester')=='Genap'?'selected':'' }}>Genap</option>
            </select>

            <select name="major" class="border rounded-lg px-3 py-2">
                <option value="">Jurusan</option>
                @foreach($classes->unique('major') as $c)
                    <option value="{{ $c->major }}"
                        {{ request('major') == $c->major ? 'selected' : '' }}>
                        {{ $c->major }}
                    </option>
                @endforeach
            </select>

            <select name="class_id" class="border rounded-lg px-3 py-2">
                <option value="">Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}"
                        {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <select name="schedule_id" class="border rounded-lg px-3 py-2">
                <option value="">Mata Pelajaran</option>

                @forelse($schedules as $s)
                    <option value="{{ $s->id }}"
                        {{ (request('schedule_id') ?? ($scheduleId ?? null)) == $s->id ? 'selected' : '' }}>
                        {{ $s->teacher->subject ?? 'Tidak ada mapel' }}
                    </option>
                @empty
                    <option value="">Tidak ada jadwal hari ini</option>
                @endforelse
            </select>

            <input type="date" name="date"
                value="{{ $date ?? date('Y-m-d') }}"
                class="border rounded-lg px-3 py-2">

            <button class="bg-blue-600 text-white rounded-lg px-3 py-2 col-span-2">
                Filter
            </button>

        </form>

        <!-- ================= FORM ABSENSI ================= -->
        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf

            <input type="hidden" name="date" value="{{ $date ?? date('Y-m-d') }}">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <div class="p-4 flex justify-between items-center border-b">
                    <h3 class="font-semibold">Daftar Siswa</h3>

                    <button type="button"
                        onclick="setAllHadir()"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600">
                        Set Semua Hadir
                    </button>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">NISN</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-center">Absensi</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">

                    @forelse($students as $i => $student)
                    <tr class="hover:bg-gray-50">

                        <td class="p-3">{{ $i+1 }}</td>
                        <td class="p-3">{{ $student->nisn }}</td>
                        <td class="p-3 font-medium">{{ $student->name }}</td>

                        <td class="p-3 text-center">

                            <input type="hidden" name="student_id[]" value="{{ $student->id }}">

                            <div class="flex justify-center gap-4">

                                <label>
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="hadir" required>
                                    Hadir
                                </label>

                                <label>
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="izin">
                                    Izin
                                </label>

                                <label>
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="sakit">
                                    Sakit
                                </label>

                                <label>
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="alpa">
                                    Alpa
                                </label>

                            </div>

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-6 text-gray-400">
                            Tidak ada siswa untuk hari ini (cek kelas & jadwal)
                        </td>
                    </tr>
                    @endforelse

                    </tbody>
                </table>

            </div>

            <!-- ACTION -->
            <div class="mt-6 flex justify-end gap-4">

                <a href="{{ route('attendance.index') }}"
                    class="px-6 py-2 border rounded-lg">
                    Batal
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Simpan
                </button>

            </div>

        </form>

    </main>

</div>

<script>
function setAllHadir() {
    document.querySelectorAll('input[value="hadir"]').forEach(el => el.checked = true);
}
</script>

@endsection