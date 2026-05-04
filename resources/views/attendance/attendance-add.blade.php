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
                @foreach($schedules as $s)
                    <option value="{{ $s->id }}"
                        {{ request('schedule_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->teacher->subject ?? '-' }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date"
                value="{{ $date ?? date('Y-m-d') }}"
                class="border rounded-lg px-3 py-2">

            <button class="bg-blue-600 text-white rounded-lg px-3 py-2 col-span-2">
                Filter
            </button>

        </form>

        <!-- ALERT -->
        <div id="alertBox" class="mb-4"></div>

        <!-- ================= FORM ================= -->
        <form id="formAttendance">

            @csrf

            <input type="hidden" name="schedule_id" value="{{ request('schedule_id') }}">
            <input type="hidden" name="date" value="{{ $date ?? date('Y-m-d') }}">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3 text-center">Absensi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($students as $student)
                    <tr class="border-t">

                        <td class="p-3">{{ $student->name }}</td>

                        <td class="p-3 text-center">

                            <input type="hidden" name="student_id[]" value="{{ $student->id }}">

                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="hadir"> Hadir</label>
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="izin"> Izin</label>
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="sakit"> Sakit</label>
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="alpa"> Alpa</label>

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center p-6 text-gray-400">
                            Tidak ada siswa
                        </td>
                    </tr>
                    @endforelse

                    </tbody>
                </table>

            </div>

            <!-- 🔥 BUTTON AREA (DITAMBAHKAN KEMBALI TANPA UBAH DESAIN) -->
            <div class="mt-4 flex gap-3">

                <!-- TOMBOL KEMBALI -->
                <a href="{{ route('attendance.index') }}"
                   class="px-6 py-2 border rounded-lg">
                    Kembali
                </a>

                <!-- TOMBOL SIMPAN -->
                <button class="px-6 py-2 bg-blue-500 text-white rounded-lg">
                    Simpan
                </button>

            </div>

        </form>

    </main>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ❗ Pastikan jQuery & ajax.js sudah ke-load
    if (typeof $ === 'undefined') {
        console.error('jQuery belum load (Vite belum jalan)');
        return;
    }

    if (typeof createData === 'undefined') {
        console.error('ajax.js belum ke-load');
        return;
    }

    // ✅ AJAX SUBMIT
    createData('#formAttendance', "{{ route('attendance.store') }}", function(res){

        let schedule = $('input[name="schedule_id"]').val();
        let students = $('input[name="student_id[]"]').length;

        // VALIDASI FRONTEND
        if (!schedule) {
            showToast('Pilih mata pelajaran dulu', 'error');
            return;
        }

        if (students === 0) {
            showToast('Tidak ada siswa', 'error');
            return;
        }

        $('#alertBox').html(`
            <div class="p-3 bg-green-100 text-green-700 rounded">
                ${res.message}
            </div>
        `);

        // reset radio saja
        $('input[type=radio]').prop('checked', false);

    });

});
</script>
@endpush