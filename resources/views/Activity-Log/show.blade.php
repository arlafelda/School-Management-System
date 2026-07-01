@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap');

    .log-mono { font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace; }

    .log-header-strip {
        background:
            repeating-linear-gradient(135deg, rgba(255,255,255,.035) 0 1px, transparent 1px 14px),
            linear-gradient(160deg, #0B0F14 0%, #10161d 100%);
    }

    .log-card-enter {
        animation: logCardEnter .5s cubic-bezier(.16,1,.3,1) both;
    }
    .log-row-enter {
        animation: logRowEnter .4s ease both;
    }
    @keyframes logCardEnter {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes logRowEnter {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @media (prefers-reduced-motion: reduce) {
        .log-card-enter, .log-row-enter { animation: none; }
    }

    .log-diff-row { border-left: 3px solid transparent; }
    .log-diff-row.is-changed  { border-left-color: #F59E0B; background: linear-gradient(90deg, #FFFBEB, transparent 40%); }
    .log-diff-row.is-added    { border-left-color: #16A34A; background: linear-gradient(90deg, #F0FDF4, transparent 40%); }
    .log-diff-row.is-removed  { border-left-color: #DC2626; background: linear-gradient(90deg, #FEF2F2, transparent 40%); }
    .log-diff-row.is-unchanged{ border-left-color: #E5E7EB; }

    .log-copy-btn:focus-visible,
    .log-back-link:focus-visible,
    .log-crumb:focus-visible {
        outline: 2px solid #4F46E5;
        outline-offset: 2px;
        border-radius: 6px;
    }
</style>

<div class="min-h-screen bg-[#F5F6F8] p-4 md:p-8">
    <div class="max-w-4xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1.5 log-mono">
            <a href="{{ route('dashboard') }}" class="log-crumb hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('activity-log.index') }}" class="log-crumb hover:text-indigo-600 transition">Log Aktivitas</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">#{{ $activityLog->id }}</span>
        </div>

        {{-- BACK BUTTON --}}
        <div class="mb-6">
            <a href="{{ route('activity-log.index') }}"
                class="log-back-link inline-flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 hover:-translate-x-0.5 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Kembali ke Daftar Log
            </a>
        </div>

        {{-- MAIN CARD --}}
        <div class="log-card-enter bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            {{-- Dark terminal-style header strip --}}
            <div class="log-header-strip px-6 py-4 flex items-center justify-between gap-4 text-white/90">
                <div class="log-mono text-sm flex items-center gap-2">
                    <span class="text-white/40">$</span>
                    <span class="text-white font-medium">log</span>
                    <span class="text-white/50">--id={{ $activityLog->id }}</span>
                </div>
                <div class="text-right log-mono">
                    <div class="text-sm text-white/90">{{ $activityLog->created_at->format('Y-m-d H:i:s') }}</div>
                    <div class="text-xs text-white/40">{{ $activityLog->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Headline --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $activityLog->action_badge_color }}">
                        {{ $activityLog->action_label }}
                    </span>
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md log-mono">{{ $activityLog->module }}</span>
                </div>
                <h2 class="text-lg font-semibold text-gray-800 leading-snug">{{ $activityLog->description }}</h2>
                @if($activityLog->target_label)
                    <div class="mt-2 inline-flex items-center gap-1.5 text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M7 7h.01M7 3h5.586a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7.172 7.172a2 2 0 01-2.828 0l-7-7A2 2 0 013 12.586V7a4 4 0 014-4z"/>
                        </svg>
                        Target: <span class="font-medium text-gray-700">{{ $activityLog->target_label }}</span>
                    </div>
                @endif
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">

                {{-- Pelaku --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Informasi Pelaku</h3>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <dt class="flex items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6"/></svg>
                                Nama User
                            </dt>
                            <dd class="font-medium text-gray-800">{{ $activityLog->user_name ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <dt class="flex items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Role
                            </dt>
                            <dd class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $activityLog->user_role ?? '-') }}</dd>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <dt class="flex items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                User ID
                            </dt>
                            <dd class="font-medium text-gray-800 log-mono">{{ $activityLog->user_id ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Teknis --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Informasi Teknis</h3>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <dt class="flex items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18 14 14 0 010-18z"/></svg>
                                IP Address
                            </dt>
                            <dd class="flex items-center gap-1.5">
                                <span class="font-mono text-xs text-gray-800 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded">{{ $activityLog->ip_address ?? '-' }}</span>
                                @if($activityLog->ip_address)
                                <button type="button" onclick="logCopy(this, '{{ $activityLog->ip_address }}')"
                                    class="log-copy-btn text-gray-300 hover:text-indigo-600 transition" title="Salin IP">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="11" height="11" rx="1.5"/><path d="M5 15V5a1.5 1.5 0 011.5-1.5H15"/></svg>
                                </button>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <dt class="flex items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 3a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V8l-5-5H6z"/><path d="M13 3v5h5"/></svg>
                                Log ID
                            </dt>
                            <dd class="font-mono text-xs text-gray-800">#{{ $activityLog->id }}</dd>
                        </div>
                        <div class="flex items-start justify-between text-sm gap-3">
                            <dt class="flex items-center gap-2 text-gray-500 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                                Browser
                            </dt>
                            <dd class="text-xs text-gray-600 text-right break-all" title="{{ $activityLog->user_agent }}">
                                {{ $activityLog->user_agent ? Str::limit($activityLog->user_agent, 40) : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>

        {{-- DATA PERUBAHAN — unified diff style --}}
        @if($activityLog->old_data || $activityLog->new_data)
        @php
            $oldData = $activityLog->old_data ?? [];
            $newData = $activityLog->new_data ?? [];

            $allKeys = array_values(array_unique(array_merge(array_keys($oldData), array_keys($newData))));

            $summary = ['changed' => 0, 'added' => 0, 'removed' => 0];
            foreach ($allKeys as $k) {
                $inOld = array_key_exists($k, $oldData);
                $inNew = array_key_exists($k, $newData);
                if ($inOld && $inNew) {
                    if ($oldData[$k] != $newData[$k]) $summary['changed']++;
                } elseif ($inNew && !$inOld) {
                    $summary['added']++;
                } elseif ($inOld && !$inNew) {
                    $summary['removed']++;
                }
            }

            $formatVal = function ($v) {
                if (is_null($v)) return '<null>';
                if (is_bool($v)) return $v ? 'true' : 'false';
                if (is_array($v)) return json_encode($v);
                return (string) $v;
            };
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden log-card-enter">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Perubahan Data
                </h3>
                <div class="flex items-center gap-3 text-xs log-mono">
                    @if($summary['changed'] > 0)
                        <span class="text-amber-600 font-medium">{{ $summary['changed'] }} diubah</span>
                    @endif
                    @if($summary['added'] > 0)
                        <span class="text-green-600 font-medium">+{{ $summary['added'] }}</span>
                    @endif
                    @if($summary['removed'] > 0)
                        <span class="text-red-600 font-medium">-{{ $summary['removed'] }}</span>
                    @endif
                </div>
            </div>

            <div class="divide-y divide-gray-50">
                @foreach($allKeys as $i => $key)
                    @php
                        $inOld = array_key_exists($key, $oldData);
                        $inNew = array_key_exists($key, $newData);

                        if ($inOld && $inNew) {
                            $state = ($oldData[$key] != $newData[$key]) ? 'changed' : 'unchanged';
                        } elseif ($inNew && !$inOld) {
                            $state = 'added';
                        } else {
                            $state = 'removed';
                        }
                    @endphp
                    <div class="log-diff-row log-row-enter is-{{ $state }} px-6 py-3 flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4"
                         data-delay="{{ min($i * 40, 400) }}">
                        <div class="sm:w-40 shrink-0 text-xs font-medium text-gray-500 uppercase tracking-wide log-mono">
                            {{ str_replace('_', ' ', $key) }}
                        </div>

                        <div class="flex-1 min-w-0 text-sm log-mono flex flex-wrap items-center gap-x-2 gap-y-1">
                            @if($state === 'changed')
                                <span class="text-red-500 line-through decoration-red-300 break-all">{{ $formatVal($oldData[$key]) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                <span class="text-green-700 font-semibold break-all">{{ $formatVal($newData[$key]) }}</span>
                            @elseif($state === 'added')
                                <span class="text-green-600 font-semibold break-all">+ {{ $formatVal($newData[$key]) }}</span>
                            @elseif($state === 'removed')
                                <span class="text-red-500 line-through decoration-red-300 break-all">- {{ $formatVal($oldData[$key]) }}</span>
                            @else
                                <span class="text-gray-500 break-all">{{ $formatVal($newData[$key]) }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<script>
    document.querySelectorAll('.log-row-enter[data-delay]').forEach(function (el) {
        el.style.animationDelay = el.getAttribute('data-delay') + 'ms';
    });

    function logCopy(btn, text) {
        navigator.clipboard.writeText(text).then(function () {
            var original = btn.innerHTML;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>';
            setTimeout(function () { btn.innerHTML = original; }, 1200);
        });
    }
</script>

@endsection