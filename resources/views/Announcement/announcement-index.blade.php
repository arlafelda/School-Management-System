@extends('layouts.app')

@section('title', 'Kelola Pengumuman')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm font-medium text-slate-400 mb-1">Panel Admin</p>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Kelola Pengumuman</h1>
    </div>
    <button onclick="openCreateModal()"
        class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Pengumuman
    </button>
</div>

@if (session('success'))
<div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
    {{ session('success') }}
</div>
@endif

<!-- FILTER -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="{{ route('announcement.index') }}" class="flex flex-wrap gap-3">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari judul..."
            class="flex-1 min-w-[180px] rounded-xl border border-slate-200 px-3.5 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-400">

        <select name="priority" class="rounded-xl border border-slate-200 px-3.5 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-400">
            <option value="">Semua Prioritas</option>
            <option value="normal" {{ request('priority') == 'normal'   ? 'selected' : '' }}>Normal</option>
            <option value="penting" {{ request('priority') == 'penting'  ? 'selected' : '' }}>Penting</option>
            <option value="mendesak" {{ request('priority') == 'mendesak' ? 'selected' : '' }}>Mendesak</option>
        </select>

        <select name="target_role" class="rounded-xl border border-slate-200 px-3.5 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-400">
            <option value="">Semua Target</option>
            <option value="all" {{ request('target_role') == 'all'     ? 'selected' : '' }}>Semua</option>
            <option value="student" {{ request('target_role') == 'student' ? 'selected' : '' }}>Siswa</option>
            <option value="teacher" {{ request('target_role') == 'teacher' ? 'selected' : '' }}>Guru</option>
            <option value="admin" {{ request('target_role') == 'admin'   ? 'selected' : '' }}>Admin</option>
        </select>

        <button type="submit" class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 transition-colors">
            Filter
        </button>
        <a href="{{ route('announcement.index') }}" class="rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-semibold px-4 py-2 transition-colors">
            Reset
        </a>
    </form>
</div>

<!-- TABEL -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Prioritas</th>
                    <th class="px-5 py-3">Target</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Penulis</th>
                    <th class="px-5 py-3">Kadaluarsa</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($announcements as $item)
                @php
                $priorityBadge = [
                'normal' => 'bg-slate-100 text-slate-600',
                'penting' => 'bg-amber-100 text-amber-700',
                'mendesak' => 'bg-red-100 text-red-700',
                ];
                $priorityIcon = [
                'normal' => '📢',
                'penting' => '⚠️',
                'mendesak' => '🚨',
                ];
                @endphp
                <tr class="hover:bg-slate-50/60 transition-colors {{ $item->trashed() ? 'opacity-50' : '' }}">
                    <td class="px-5 py-3.5">
                        <div class="font-semibold text-slate-700">{{ $item->title }}</div>
                        @if ($item->trashed())
                        <span class="text-xs text-slate-400">Terhapus</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $priorityBadge[$item->priority] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $priorityIcon[$item->priority] ?? '' }} {{ ucfirst($item->priority) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600">{{ ucfirst($item->target_role) }}</td>
                    <td class="px-5 py-3.5">
                        @if ($item->is_active)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $item->author->name ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">
                        {{ $item->expired_at ? $item->expired_at->format('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="inline-flex items-center gap-1.5">
                            <button onclick="openModal('modal-{{ $item->id }}')"
                                title="Lihat detail"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>

                            @if (!$item->trashed())
                            {{-- SESUDAH --}}
                            <button
                                title="Edit"
                                data-edit="{{ Js::from([
        'id'          => $item->id,
        'title'       => $item->title,
        'content'     => $item->content,
        'priority'    => $item->priority,
        'target_role' => $item->target_role,
        'expired_at'  => $item->expired_at?->format('Y-m-d'),
        'is_active'   => (bool) $item->is_active,
    ]) }}"
                                onclick="openEditModal(JSON.parse(this.dataset.edit))"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-amber-500 hover:bg-amber-50 transition-colors">

                                <form action="{{ route('announcement.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus pengumuman ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('announcement.restore', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Pulihkan"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-emerald-500 hover:bg-emerald-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </form>

                                <form action="{{ route('announcement.forceDelete', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan!')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus permanen"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                        </div>
                    </td>
                </tr>
                {{-- Modal detail (read-only) untuk baris ini --}}
                @include('announcement._modal', ['ann' => $item])
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                        Belum ada pengumuman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 border-t border-slate-100">
        {{ $announcements->links() }}
    </div>
</div>

{{-- Modal form tambah/edit (shared, satu untuk semua baris) --}}
@include('announcement._modal-form')

@endsection

@push('scripts')
<script>
    // openModal/closeModal didefinisikan di announcement._modal-form dan announcement._modal.
    // Disediakan juga di sini sebagai fallback jika halaman ini suatu saat dirender tanpa kedua partial tersebut.
    if (typeof openModal !== 'function') {
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }
        document.addEventListener('click', function(e) {
            const backdrop = e.target.closest('[data-modal-backdrop]');
            if (backdrop) closeModal(backdrop.dataset.modalBackdrop);
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[data-modal-backdrop]').forEach(el => {
                    if (!el.classList.contains('hidden')) closeModal(el.dataset.modalBackdrop);
                });
            }
        });
    }
</script>
@endpush