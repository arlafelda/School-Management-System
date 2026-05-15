@extends('layouts.app')

@section('title', 'Daftar Admin')

@section('content')

<!-- BREADCRUMB -->
<div class="mb-4 text-sm text-gray-500">
    <a href="{{ route('dashboard.super_admin') }}"
       class="hover:text-blue-600">
        Dashboard
    </a>

    <span class="mx-2">/</span>

    <span class="text-gray-700 font-medium">
        Kelola Admin
    </span>
</div>

<div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

<div class="flex justify-between items-center mb-4">

    <h1 class="text-2xl font-bold">Daftar Admin</h1>

    <div class="flex gap-2">

        <!-- tombol arsip -->
        <a href="{{ route('admin.archived') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Arsip
        </a>

        <!-- tambah -->
        <a href="{{ route('admin.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
            + Tambah Admin
        </a>

    </div>
</div>

<div class="bg-white rounded-xl shadow border overflow-x-auto">

    <table class="w-full text-sm min-w-[700px]">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 text-left">User</th>
                <th class="p-4 text-center">Role</th>
                <th class="p-4 text-center">ID</th>
                <th class="p-4 text-center">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($admins as $user)
            <tr id="row-{{ $user->slug }}">

                <!-- detail -->
                <td class="p-4 cursor-pointer admin-show"
                    data-url="{{ route('admin.show', $user->slug) }}">
                    <p class="font-semibold text-blue-600">
                        {{ $user->name }}
                    </p>
                    <p class="text-gray-500 text-xs">
                        {{ $user->email }}
                    </p>
                </td>

                <td class="p-4 text-center admin-show"
                    data-url="{{ route('admin.show', $user->slug) }}">
                    {{ ucfirst($user->role) }}
                </td>

                <td class="p-4 text-center admin-show"
                    data-url="{{ route('admin.show', $user->slug) }}">
                    #{{ $user->id }}
                </td>

                <td class="p-4 text-center admin-show"
                    data-url="{{ route('admin.show', $user->slug) }}">
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                        Aktif
                    </span>
                </td>

                <td class="p-4 text-center">

                    <a href="{{ route('admin.edit', $user->slug) }}"
                       class="text-blue-600 hover:underline">
                        Edit
                    </a>

                    <button type="button"
                        class="text-red-600 ml-2 btn-delete"
                        data-url="{{ route('admin.delete', $user->slug) }}"
                        data-row="row-{{ $user->slug }}">
                        Arsipkan
                    </button>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" class="text-center p-6 text-gray-500">
                    Data admin kosong
                </td>
            </tr>
            @endforelse

        </tbody>

    </table>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // klik detail
    document.addEventListener('click', function (e) {
        let row = e.target.closest('.admin-show');

        if (row) {
            window.location.href = row.dataset.url;
        }
    });

    // archive
    document.addEventListener('click', function (e) {

        let btn = e.target.closest('.btn-delete');
        if (!btn) return;

        if (!confirm('Yakin ingin memindahkan data ke arsip?')) {
            return;
        }

        let url = btn.dataset.url;
        let rowId = btn.dataset.row;

        if (typeof deleteData === 'undefined') {
            console.error('deleteData function belum tersedia');
            return;
        }

        deleteData(url, function () {

            let row = document.getElementById(rowId);
            if (row) row.remove();

            let alertBox = document.getElementById("alertBox");

            alertBox.classList.remove("hidden");
            alertBox.className =
                "mb-4 p-3 rounded text-sm bg-green-100 text-green-700";

            alertBox.innerText =
                "Data berhasil dipindahkan ke arsip";

            setTimeout(() => {
                alertBox.classList.add("hidden");
            }, 3000);

        });

    });

});
</script>
@endpush