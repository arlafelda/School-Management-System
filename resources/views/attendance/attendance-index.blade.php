@extends('layouts.app')

@section('content')

@php
$user = auth()->user();

$studentClass = $user->role === 'student'
? optional(optional($user->student)->class)
: null;
@endphp

<div class="p-8">

    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>
        /
        <span class="text-gray-700 font-medium">
            Manajemen Absensi
        </span>
    </div>


    <!-- TITLE -->
    <div class="mb-6 flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">
                Manajemen Absensi
            </h2>

            <p class="text-gray-500 text-sm">
                Kelola kehadiran siswa
            </p>
        </div>

        <div class="flex gap-3">

            <!-- REKAP -->
            <a href="{{ route('attendance.recap') }}"
                class="px-4 py-2 bg-green-500 text-white rounded-lg">
                Rekap
            </a>

            <!-- INPUT -->
            @if(in_array($user->role,['super_admin','admin','teacher']))
            <a href="{{ route('attendance.create') }}"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Input Absensi
            </a>
            @endif

        </div>

    </div>


    <!-- ================= FILTER ================= -->
    <form method="GET"
        action="{{ route('attendance.index') }}"
        class="bg-white p-4 rounded-xl shadow-sm mb-6 grid md:grid-cols-4 gap-4">

        {{-- CLASS --}}
        @if($user->role === 'student')

        <input type="text"
            value="{{ $studentClass->name ?? '-' }}"
            class="border rounded-lg px-3 py-2 bg-gray-100"
            disabled>

        <input type="hidden"
            name="class_id"
            value="{{ $studentClass->id ?? '' }}">

        @else

        <select name="class_id"
            class="border rounded-lg px-3 py-2">

            <option value="">
                Semua Kelas
            </option>

            @foreach($classes as $c)
            <option value="{{ $c->id }}"
                {{ request('class_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
            @endforeach

        </select>

        @endif


        {{-- STATUS --}}
        <select name="status"
            class="border rounded-lg px-3 py-2">

            <option value="">
                Semua Status
            </option>

            <option value="hadir"
                {{ request('status') == 'hadir' ? 'selected' : '' }}>
                Hadir
            </option>

            <option value="izin"
                {{ request('status') == 'izin' ? 'selected' : '' }}>
                Izin
            </option>

            <option value="sakit"
                {{ request('status') == 'sakit' ? 'selected' : '' }}>
                Sakit
            </option>

            <option value="alpa"
                {{ request('status') == 'alpa' ? 'selected' : '' }}>
                Alpa
            </option>

        </select>


        {{-- DATE --}}
        <div>
            <input type="date"
                id="tanggalInput"
                name="date"
                value="{{ request('date') }}"
                class="border rounded-lg px-3 py-2 w-full">

            <small id="tanggalPreview"
                class="text-gray-500 text-xs block mt-1"></small>
        </div>


        <button class="bg-blue-600 text-white rounded-lg px-3 py-2">
            Filter
        </button>

    </form>


    <!-- STATS -->
    <div class="grid md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Kehadiran</p>
            <h3 class="text-2xl font-bold text-blue-600 text-right">
                {{ number_format($persen, 1) }}%
            </h3>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Total Data</p>
            <h3 class="text-2xl font-bold text-right">
                {{ number_format($attendances->count()) }}
            </h3>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Hadir</p>
            <h3 class="text-2xl font-bold text-purple-600 text-right">
                {{ $attendances->where('status','hadir')->count() }}
            </h3>
        </div>

    </div>


    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Kelas</th>
                    <th class="p-3 text-left">Mapel</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($attendances as $i => $a)

                @php
                $isOwnerTeacher = false;

                if ($user->role === 'teacher') {
                $isOwnerTeacher =
                optional($a->schedule)->teacher &&
                $a->schedule->teacher->user_id == $user->id;
                }
                @endphp

                <tr id="row-{{ $a->id }}"
                    class="hover:bg-gray-50">

                    <td class="p-3">
                        {{ $i + 1 }}
                    </td>

                    <td class="p-3 font-medium">
                        {{ $a->student->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $a->student->class->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $a->schedule->subject ?? ($a->schedule->teacher->subject ?? '-') }}
                    </td>

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($a->date)->locale('id')->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-3">

                        @php
                        $statusClass = match($a->status) {
                        'hadir' => 'bg-green-100 text-green-600',
                        'izin' => 'bg-yellow-100 text-yellow-600',
                        'sakit' => 'bg-blue-100 text-blue-600',
                        default => 'bg-red-100 text-red-600'
                        };
                        @endphp

                        <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">
                            {{ ucfirst($a->status) }}
                        </span>

                    </td>

                    <td class="p-3 text-right space-x-2">

                        @if(in_array($user->role,['super_admin','admin']))

                        <a href="{{ route('attendance.edit',$a->id) }}"
                            class="text-blue-500 hover:underline">
                            Edit
                        </a>

                        <button
                            data-id="{{ $a->id }}"
                            data-url="{{ route('attendance.destroy',$a->id) }}"
                            class="btn-delete text-red-500 hover:underline">
                            Hapus
                        </button>

                        @elseif($user->role === 'teacher' && $isOwnerTeacher)

                        <a href="{{ route('attendance.edit',$a->id) }}"
                            class="text-blue-500 hover:underline">
                            Edit
                        </a>

                        @else

                        <span class="text-gray-400 text-xs">
                            View Only
                        </span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7"
                        class="p-6 text-center text-gray-400">
                        Data tidak ditemukan
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

        const tanggalInput =
            document.getElementById('tanggalInput');

        const preview =
            document.getElementById('tanggalPreview');

        function formatTanggalIndonesia(value) {
            if (!value) return '';

            const tgl = new Date(value);

            return tgl.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        if (tanggalInput && preview) {
            preview.innerText =
                formatTanggalIndonesia(tanggalInput.value);

            tanggalInput.addEventListener('change', function() {
                preview.innerText =
                    formatTanggalIndonesia(this.value);
            });
        }


        if (typeof deleteData === 'undefined') {
            console.error('ajax.js belum load');
            return;
        }

        document.querySelectorAll('.btn-delete')
            .forEach(btn => {

                btn.addEventListener('click', function() {

                    if (!confirm('Yakin hapus data ini?')) return;

                    let id = this.dataset.id;
                    let url = this.dataset.url;

                    deleteData(url, function() {
                        document.getElementById('row-' + id)?.remove();
                    });

                });

            });

    });
</script>
@endpush