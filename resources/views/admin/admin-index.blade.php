@extends('layouts.app')

@section('title', 'Daftar Admin')

@section('content')

<div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-bold">Daftar Admin</h1>

    <a href="{{ route('admin.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
        + Tambah Admin
    </a>
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

            @foreach($admins as $user)
            <tr id="row-{{ $user->id }}">

                <td class="p-4 cursor-pointer admin-show" data-id="{{ $user->id }}">
                    <p class="font-semibold text-blue-600">{{ $user->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $user->email }}</p>
                </td>

                <td class="p-4 text-center admin-show" data-id="{{ $user->id }}">
                    {{ ucfirst($user->role) }}
                </td>

                <td class="p-4 text-center admin-show" data-id="{{ $user->id }}">
                    #{{ $user->id }}
                </td>

                <td class="p-4 text-center admin-show" data-id="{{ $user->id }}">
                    {{ $user->archived == 0 ? 'Aktif' : 'Nonaktif' }}
                </td>

                <td class="p-4 text-center">

                    <a href="{{ route('admin.edit', $user->id) }}"
                       class="text-blue-600">
                        Edit
                    </a>

                    <button type="button"
                            class="text-red-600 ml-2 btn-delete"
                            data-id="{{ $user->id }}">
                        Hapus
                    </button>

                </td>

            </tr>
            @endforeach

        </tbody>

    </table>

</div>

@endsection

@push('scripts')

<script>
/* =========================
   SHOW DETAIL
========================= */
document.addEventListener('click', function (e) {
    let row = e.target.closest('.admin-show');

    if (row) {
        let id = row.dataset.id;
        window.location.href = "/admin/" + id;
    }
});


/* =========================
   DELETE AJAX (pakai ajax.js)
========================= */
document.addEventListener('click', function (e) {

    let btn = e.target.closest('.btn-delete');
    if (!btn) return;

    let id = btn.dataset.id;

    deleteData("/admin/" + id, function () {

        let row = document.getElementById("row-" + id);
        if (row) row.remove();

        let alertBox = document.getElementById("alertBox");

        alertBox.classList.remove("hidden");
        alertBox.classList.add("bg-green-100", "text-green-700");

        alertBox.innerText = "Data berhasil dihapus";
    });

});
</script>

@endpush