@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            {{ __('Profil Saya') }}
        </h2>

        {{-- Status flash message (opsional, kalau ada redirect dari edit) --}}
        @if (session('status'))
            <div class="bg-green-100 text-green-700 text-sm rounded-lg p-4">
                {{ session('status') }}
            </div>
        @endif

        {{-- Info dasar, sama untuk semua role --}}
        @php
            // Hitung dulu class badge sesuai role, supaya hanya satu set class
            // yang ditulis di atribut class (menghindari false-positive
            // "cssConflict" dari Tailwind IntelliSense).
            $roleBadgeClass = match ($user->role) {
                'admin'   => 'bg-red-100 text-red-700',
                'teacher' => 'bg-blue-100 text-blue-700',
                'student' => 'bg-green-100 text-green-700',
                default   => 'bg-gray-100 text-gray-700',
            };
        @endphp
        <div class="bg-white p-6 shadow sm:rounded-lg">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16 rounded-full bg-indigo-500 flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <span class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full {{ $roleBadgeClass }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ===================== ROLE: ADMIN ===================== --}}
        {{-- tbl_admins tidak ada, jadi info admin cukup dari tbl_users --}}
        @if ($user->role === 'admin')
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Informasi Akun</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Slug</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $user->slug ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ $user->archived ? 'Nonaktif' : 'Aktif' }}
                        </dd>
                    </div>
                </dl>
            </div>

        {{-- ===================== ROLE: TEACHER ===================== --}}
        {{-- data dari tbl_teachers (relasi $user->teacher) --}}
        @elseif ($user->role === 'teacher')
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Informasi Kepegawaian</h3>

                @if ($profile)
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">NIP</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->nip ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Jabatan</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->position ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">No. Telepon</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->phone ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-gray-500">Alamat</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->address ?? '-' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500">Data kepegawaian belum tersedia.</p>
                @endif
            </div>

        {{-- ===================== ROLE: STUDENT ===================== --}}
        {{-- data dari tbl_students (relasi $user->student) --}}
        @elseif ($user->role === 'student')
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Informasi Siswa</h3>

                @if ($profile)
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">NISN</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->nisn ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">NIS</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->nis ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Jenis Kelamin</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ $profile->gender === 'L' ? 'Laki-laki' : ($profile->gender === 'P' ? 'Perempuan' : '-') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Tempat, Tanggal Lahir</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ $profile->birth_place ?? '-' }},
                                {{ $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('d M Y') : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">No. Telepon</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Kelas</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ optional($profile->class)->name ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Jurusan</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->major ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Status</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($profile->status ?? '-') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-gray-500">Alamat</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->address ?? '-' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500">Data siswa belum tersedia.</p>
                @endif
            </div>

            @if ($profile)
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Informasi Orang Tua</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Nama Ayah</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->father_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Nama Ibu</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->mother_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">No. Telepon Orang Tua</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->parent_phone ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-gray-500">Alamat Orang Tua</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->parent_address ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection