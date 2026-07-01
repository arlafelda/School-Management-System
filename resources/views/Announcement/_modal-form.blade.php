{{-- Modal Tambah / Edit Pengumuman --}}
<div id="modal-form"
    class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm"
    data-modal-backdrop="modal-form">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6"
        onclick="event.stopPropagation()">

        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-slate-800" id="modal-form-title">Tambah Pengumuman</h2>
            <button onclick="closeModal('modal-form')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="modal-form-el" method="POST" action="{{ route('announcement.store') }}">
            @csrf
            <div id="modal-form-method"></div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul</label>
                    <input type="text" name="title" id="form-title" required
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Isi Pengumuman</label>
                    <textarea name="content" id="form-content" rows="4" required
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Prioritas</label>
                        <select name="priority" id="form-priority"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                            <option value="normal">Normal</option>
                            <option value="penting">Penting</option>
                            <option value="mendesak">Mendesak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Target</label>
                        <select name="target_role" id="form-target_role"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                            <option value="all">Semua</option>
                            <option value="student">Siswa</option>
                            <option value="teacher">Guru</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kadaluarsa</label>
                        <input type="date" name="expired_at" id="form-expired_at"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" id="form-is_active" value="1"
                                class="w-4 h-4 rounded accent-violet-600">
                            <span class="text-sm font-medium text-slate-700">Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('modal-form')"
                    class="rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold px-4 py-2 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

    // Tutup saat klik backdrop (bukan konten modal)
    document.addEventListener('click', function (e) {
        const backdrop = e.target.closest('[data-modal-backdrop]');
        if (backdrop && e.target === backdrop) {
            closeModal(backdrop.dataset.modalBackdrop);
        }
    });

    // Tutup saat tekan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-modal-backdrop]').forEach(el => {
                if (!el.classList.contains('hidden')) {
                    closeModal(el.dataset.modalBackdrop);
                }
            });
        }
    });

    function openCreateModal() {
        const form = document.getElementById('modal-form-el');
        form.action = "{{ route('announcement.store') }}";
        document.getElementById('modal-form-method').innerHTML = '';
        document.getElementById('modal-form-title').textContent = 'Tambah Pengumuman';
        document.getElementById('form-title').value = '';
        document.getElementById('form-content').value = '';
        document.getElementById('form-priority').value = 'normal';
        document.getElementById('form-target_role').value = 'all';
        document.getElementById('form-expired_at').value = '';
        document.getElementById('form-is_active').checked = true;
        openModal('modal-form');
    }

    function openEditModal(data) {
        const form = document.getElementById('modal-form-el');
        form.action = `/announcements/${data.id}`;
        document.getElementById('modal-form-method').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modal-form-title').textContent = 'Edit Pengumuman';
        document.getElementById('form-title').value = data.title;
        document.getElementById('form-content').value = data.content;
        document.getElementById('form-priority').value = data.priority;
        document.getElementById('form-target_role').value = data.target_role;
        document.getElementById('form-expired_at').value = data.expired_at ?? '';
        document.getElementById('form-is_active').checked = data.is_active;
        openModal('modal-form');
    }
</script>