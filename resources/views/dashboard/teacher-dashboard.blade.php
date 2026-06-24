@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<div class="guru-dash">

    {{-- ===== HEADER ===== --}}
    <div class="dash-header">
        <div class="dash-header__left">
            <span class="dash-header__eyebrow">Selamat datang kembali 👋</span>
            <h1 class="dash-header__title">Dashboard Guru</h1>
            <p class="dash-header__sub">{{ $today }}</p>
        </div>
        <div class="dash-header__badge">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
            </svg>
            Guru Aktif
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="stat-grid">

        @if($homeroomClass)
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="stat-card__body">
                <p class="stat-card__label">Siswa Wali Kelas</p>
                <p class="stat-card__value">{{ number_format($totalStudents) }}</p>
                <p class="stat-card__sub">{{ $homeroomClass->name }}</p>
            </div>
        </div>
        @endif

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--indigo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
            </div>
            <div class="stat-card__body">
                <p class="stat-card__label">Kelas Diajar</p>
                <p class="stat-card__value">{{ number_format($totalClasses) }}</p>
                <p class="stat-card__sub">kelas aktif</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--emerald">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div class="stat-card__body">
                <p class="stat-card__label">Mata Pelajaran</p>
                <p class="stat-card__value">{{ number_format($totalSubjects) }}</p>
                <p class="stat-card__sub">mapel diampu</p>
            </div>
        </div>

        <div class="stat-card stat-card--accent">
            <div class="stat-card__icon stat-card__icon--white">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div class="stat-card__body">
                <p class="stat-card__label stat-card__label--light">Jadwal Hari Ini</p>
                <p class="stat-card__value stat-card__value--light">{{ $todayCount }}</p>
                <p class="stat-card__sub stat-card__sub--light">sesi mengajar</p>
            </div>
        </div>

    </div>

    {{-- ===== MAIN GRID ===== --}}
    <div class="main-grid">

        {{-- Mata Pelajaran --}}
        <div class="card">
            <div class="card__header">
                <h2 class="card__title">Mata Pelajaran Diampu</h2>
                <span class="card__badge">{{ $subjects->count() }} mapel</span>
            </div>

            @if($subjects->isEmpty())
                <div class="empty-state">
                    <p class="empty-state__icon">📚</p>
                    <p class="empty-state__text">Belum ada mata pelajaran</p>
                </div>
            @else
                <ul class="subject-list">
                    @foreach($subjects as $subject)
                        <li class="subject-item">
                            <span class="subject-dot"></span>
                            <span class="subject-name">{{ $subject->name }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Jadwal Hari Ini --}}
        <div class="card card--wide">
            <div class="card__header">
                <h2 class="card__title">Jadwal Hari Ini</h2>
                @if($todayCount > 0)
                    <span class="card__badge card__badge--blue">{{ $todayCount }} sesi</span>
                @endif
            </div>

            @forelse($todaySchedules as $i => $schedule)
                <div class="schedule-item">
                    <div class="schedule-item__timeline">
                        <div class="timeline-dot {{ $loop->first ? 'timeline-dot--active' : '' }}"></div>
                        @if(!$loop->last)
                            <div class="timeline-line"></div>
                        @endif
                    </div>
                    <div class="schedule-item__time">
                        <p class="time-start">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                        <p class="time-end">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    </div>
                    <div class="schedule-item__info">
                        <p class="schedule-subject">{{ optional($schedule->subject)->name ?? '-' }}</p>
                        <p class="schedule-class">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:3px;vertical-align:-1px">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            {{ optional($schedule->class)->name ?? '-' }}
                        </p>
                    </div>
                    @if($loop->first)
                        <span class="schedule-badge">Sekarang</span>
                    @endif
                </div>
            @empty
                <div class="empty-state empty-state--center">
                    <p class="empty-state__icon">📭</p>
                    <p class="empty-state__text">Tidak ada jadwal hari ini</p>
                    <p class="empty-state__hint">Nikmati hari libur Anda!</p>
                </div>
            @endforelse
        </div>

    </div>

</div>

<style>
/* ===== RESET / BASE ===== */
.guru-dash {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: #0F172A;
    padding: 1.5rem 1rem;
    max-width: 1280px;
    margin: 0 auto;
}

/* ===== HEADER ===== */
.dash-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.75rem;
    gap: 1rem;
    flex-wrap: wrap;
}
.dash-header__eyebrow {
    display: block;
    font-size: 0.75rem;
    color: #3B82F6;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 0.25rem;
}
.dash-header__title {
    font-size: 1.875rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
    margin: 0 0 0.25rem;
}
.dash-header__sub {
    font-size: 0.8125rem;
    color: #94A3B8;
    margin: 0;
}
.dash-header__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.375rem 0.875rem;
    border-radius: 999px;
    white-space: nowrap;
    align-self: center;
}

/* ===== STAT GRID ===== */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (min-width: 640px) {
    .stat-grid { grid-template-columns: repeat(4, 1fr); }
}

.stat-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
    transition: box-shadow 0.2s;
    border: 1px solid #F1F5F9;
}
.stat-card:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,.12);
}
.stat-card--accent {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    border-color: transparent;
}
.stat-card__icon {
    width: 40px;
    height: 40px;
    border-radius: 0.625rem;
    background: #EFF6FF;
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-card__icon--indigo { background: #EEF2FF; color: #6366F1; }
.stat-card__icon--emerald { background: #ECFDF5; color: #10B981; }
.stat-card__icon--white { background: rgba(255,255,255,0.2); color: #fff; }
.stat-card__label {
    font-size: 0.7rem;
    color: #94A3B8;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin: 0 0 0.125rem;
}
.stat-card__label--light { color: rgba(255,255,255,0.7); }
.stat-card__value {
    font-size: 1.625rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin: 0 0 0.25rem;
}
.stat-card__value--light { color: #fff; }
.stat-card__value--muted { color: #CBD5E1; }
.stat-card__sub {
    font-size: 0.7rem;
    color: #3B82F6;
    font-weight: 500;
    margin: 0;
}
.stat-card__sub--muted { color: #CBD5E1; }
.stat-card__sub--light { color: rgba(255,255,255,0.65); }

/* ===== MAIN GRID ===== */
.main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}
@media (min-width: 1024px) {
    .main-grid { grid-template-columns: 1fr 2fr; }
}

/* ===== CARD ===== */
.card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    border: 1px solid #F1F5F9;
}
.card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}
.card__title {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
}
.card__badge {
    font-size: 0.6875rem;
    font-weight: 600;
    background: #F1F5F9;
    color: #64748B;
    padding: 0.2rem 0.625rem;
    border-radius: 999px;
}
.card__badge--blue { background: #EFF6FF; color: #2563EB; }

/* ===== SUBJECT LIST ===== */
.subject-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}
.subject-item {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.625rem 0.75rem;
    border-radius: 0.625rem;
    background: #F8FAFC;
    transition: background 0.15s;
}
.subject-item:hover { background: #EFF6FF; }
.subject-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #3B82F6;
    flex-shrink: 0;
}
.subject-name {
    font-size: 0.8125rem;
    color: #334155;
    font-weight: 500;
}

/* ===== SCHEDULE ===== */
.schedule-item {
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
    padding: 0.75rem 0;
    position: relative;
}
.schedule-item__timeline {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    padding-top: 0.25rem;
}
.timeline-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #CBD5E1;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #CBD5E1;
    flex-shrink: 0;
    z-index: 1;
}
.timeline-dot--active {
    background: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
}
.timeline-line {
    width: 2px;
    flex: 1;
    min-height: 32px;
    background: repeating-linear-gradient(
        to bottom,
        #E2E8F0 0px, #E2E8F0 4px,
        transparent 4px, transparent 8px
    );
    margin-top: 4px;
}
.schedule-item__time {
    min-width: 52px;
    flex-shrink: 0;
}
.time-start {
    font-size: 0.875rem;
    font-weight: 700;
    color: #1E40AF;
    margin: 0;
    line-height: 1.3;
}
.time-end {
    font-size: 0.7rem;
    color: #94A3B8;
    margin: 0;
}
.schedule-item__info { flex: 1; }
.schedule-subject {
    font-size: 0.875rem;
    font-weight: 600;
    color: #0F172A;
    margin: 0 0 0.125rem;
}
.schedule-class {
    font-size: 0.75rem;
    color: #64748B;
    margin: 0;
}
.schedule-badge {
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    background: #DCFCE7;
    color: #15803D;
    padding: 0.2rem 0.5rem;
    border-radius: 999px;
    align-self: center;
    flex-shrink: 0;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}
.empty-state--center { padding: 2.5rem 1rem; }
.empty-state__icon { font-size: 2rem; margin: 0 0 0.5rem; }
.empty-state__text {
    font-size: 0.8125rem;
    color: #94A3B8;
    font-weight: 500;
    margin: 0 0 0.25rem;
}
.empty-state__hint {
    font-size: 0.75rem;
    color: #CBD5E1;
    margin: 0;
}
</style>

@endsection