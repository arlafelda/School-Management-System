{{-- Modal Detail Pengumuman (read-only) --}}
<div id="modal-{{ $ann->id }}"
    class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm"
    data-modal-backdrop="modal-{{ $ann->id }}">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6"
        onclick="event.stopPropagation()">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">Detail Pengumuman</h2>
            <button onclick="closeModal('modal-{{ $ann->id }}')"
                class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="space-y-3 text-sm text-slate-700">
            <div>
                <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Judul</span>
                <p class="mt-0.5 font-semibold text-slate-800">{{ $ann->title }}</p>
            </div>
            <div>
                <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Isi</span>
                <p class="mt-0.5 whitespace-pre-line leading-relaxed">{{ $ann->content }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Prioritas</span>
                    <p class="mt-0.5">{{ ucfirst($ann->priority) }}</p>
                </div>
                <div>
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Target</span>
                    <p class="mt-0.5">{{ ucfirst($ann->target_role) }}</p>
                </div>
                <div>
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Status</span>
                    <p class="mt-0.5">{{ $ann->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Kadaluarsa</span>
                    <p class="mt-0.5">{{ $ann->expired_at ? $ann->expired_at->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Penulis</span>
                    <p class="mt-0.5">{{ optional($ann->author)->name ?? 'Admin' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-wide">Diterbitkan</span>
                    <p class="mt-0.5">
                        {{ optional($ann->published_at ?? $ann->created_at)?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-5">
            <button onclick="closeModal('modal-{{ $ann->id }}')"
                class="rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 hover:bg-slate-50 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>