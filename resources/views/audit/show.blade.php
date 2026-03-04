@extends('layouts.app')

@section('header', 'Audit Log Detail')

@section('content')
    @php
        $actionColors = [
            'sale_created' => ['bg' => 'emerald', 'icon' => 'fa-cash-register'],
            'stock_in' => ['bg' => 'blue', 'icon' => 'fa-boxes-stacking'],
            'price_changed' => ['bg' => 'amber', 'icon' => 'fa-tag'],
            'product_created' => ['bg' => 'indigo', 'icon' => 'fa-plus'],
            'product_updated' => ['bg' => 'violet', 'icon' => 'fa-pen'],
            'product_deleted' => ['bg' => 'rose', 'icon' => 'fa-trash'],
        ];
        $meta = $actionColors[$auditLog->action] ?? ['bg' => 'slate', 'icon' => 'fa-circle-info'];
        $color = $meta['bg'];
        $icon = $meta['icon'];

        $allKeys = collect(array_keys($auditLog->old_values ?? []))
            ->merge(array_keys($auditLog->new_values ?? []))
            ->unique()
            ->sort()
            ->values();
    @endphp

    <div class="flex flex-col gap-6 pb-20 max-w-5xl mx-auto">
        <div class="flex items-center gap-3">
            <a href="{{ route('audit-logs.index') }}"
                class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Back to Audit Logs
            </a>
            <span class="text-slate-200">/</span>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Entry #{{ $auditLog->id }}</span>
        </div>


        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center gap-6">

                <div class="h-16 w-16 rounded-2xl bg-{{ $color }}-50 flex items-center justify-center shrink-0">
                    <i class="fas {{ $icon }} text-2xl text-{{ $color }}-500"></i>
                </div>

                <div class="flex-1">
                    <span
                        class="inline-flex items-center rounded-xl bg-{{ $color }}-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-{{ $color }}-600 border border-{{ $color }}-100 mb-2">
                        {{ ucwords(str_replace('_', ' ', $auditLog->action)) }}
                    </span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        {{ $auditLog->auditable_type }}
                        <span class="text-slate-400 font-mono text-lg">#{{ $auditLog->auditable_id }}</span>
                    </h2>
                    <p class="text-xs font-semibold text-slate-400 mt-1">
                        {{ $auditLog->created_at->format('l, F d Y — h:i:s A') }}
                        &nbsp;·&nbsp;
                        {{ $auditLog->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>


            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8">
                <div class="bg-slate-50 rounded-2xl px-5 py-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Log ID</p>
                    <p class="text-sm font-black text-slate-800 font-mono">#{{ $auditLog->id }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl px-5 py-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Performed By</p>
                    @if ($auditLog->user)
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($auditLog->user->name) }}&background=6366f110&color=6366f1&bold=true&size=64"
                                class="h-5 w-5 rounded-md" alt="{{ $auditLog->user->name }}">
                            <span class="text-sm font-black text-slate-800">{{ $auditLog->user->name }}</span>
                        </div>
                    @else
                        <span class="text-sm font-bold text-slate-400 italic">System</span>
                    @endif
                </div>
                <div class="bg-slate-50 rounded-2xl px-5 py-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Role</p>
                    <p class="text-sm font-black text-slate-800">{{ $auditLog->user->role ?? '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl px-5 py-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Record</p>
                    <p class="text-sm font-black text-slate-800 font-mono">{{ $auditLog->auditable_type }}
                        #{{ $auditLog->auditable_id }}</p>
                </div>
            </div>

            <i class="fas fa-shield-halved absolute right-[-20px] top-[-20px] text-[10rem] opacity-[0.02]"></i>
        </div>

        @if ($allKeys->isNotEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <i class="fas fa-code-compare text-indigo-500 text-sm"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Changes Breakdown</h3>
                       
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                       
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($allKeys as $key)
                                @php
                                    $old = $auditLog->old_values[$key] ?? null;
                                    $new = $auditLog->new_values[$key] ?? null;
                                    $changed = $old !== $new;
                                @endphp
                                <tr class="{{ $changed ? 'bg-amber-50/30' : '' }} hover:bg-slate-50/50 transition-colors">

                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-2">
                                            @if ($changed)
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                            @else
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-200 shrink-0"></span>
                                            @endif
                                            <span class="text-xs font-black text-slate-700 capitalize">
                                                {{ ucwords(str_replace('_', ' ', $key)) }}
                                            </span>
                                        </div>
                                    </td>


                                    <td class="px-6 py-4">
                                        @if (is_null($old))
                                            <span class="text-[11px] text-slate-300 italic">—</span>
                                        @elseif(is_array($old) || is_object($old))
                                            <pre class="text-[10px] font-mono text-rose-600 bg-rose-50 rounded-xl px-3 py-2 whitespace-pre-wrap break-all">{{ json_encode($old, JSON_PRETTY_PRINT) }}</pre>
                                        @elseif(is_bool($old))
                                            <span
                                                class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-lg {{ $old ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                                <i class="fas {{ $old ? 'fa-check' : 'fa-xmark' }} text-[8px]"></i>
                                                {{ $old ? 'true' : 'false' }}
                                            </span>
                                        @else
                                            <span
                                                class="text-sm font-semibold {{ $changed ? 'text-rose-600 line-through decoration-rose-300' : 'text-slate-700' }}">
                                                {{ $old }}
                                            </span>
                                        @endif
                                    </td>


                                    <td class="px-6 py-4">
                                        @if (is_null($new))
                                            <span class="text-[11px] text-slate-300 italic">—</span>
                                        @elseif(is_array($new) || is_object($new))
                                            <pre class="text-[10px] font-mono text-emerald-700 bg-emerald-50 rounded-xl px-3 py-2 whitespace-pre-wrap break-all">{{ json_encode($new, JSON_PRETTY_PRINT) }}</pre>
                                        @elseif(is_bool($new))
                                            <span
                                                class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-lg {{ $new ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                                <i class="fas {{ $new ? 'fa-check' : 'fa-xmark' }} text-[8px]"></i>
                                                {{ $new ? 'true' : 'false' }}
                                            </span>
                                        @else
                                            <span
                                                class="text-sm font-bold {{ $changed ? 'text-emerald-700' : 'text-slate-700' }}">
                                                {{ $new }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div
                class="bg-white rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center justify-center py-20 gap-4">
                <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 text-2xl">
                    <i class="fas fa-equals"></i>
                </div>
                <p class="text-sm font-black text-slate-300 uppercase tracking-widest">No change data recorded</p>
            </div>
        @endif


        <div class="flex items-center justify-between">
            <a href="{{ route('audit-logs.index') }}"
                class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 rounded-2xl px-6 py-3 text-sm font-black uppercase tracking-widest transition-all shadow-sm">
                <i class="fas fa-arrow-left text-xs"></i> Back to Logs
            </a>
            <span class="text-[10px] font-semibold text-slate-300 uppercase tracking-widest">
                Audit Entry &bull; {{ $auditLog->created_at->format('Y-m-d H:i:s') }} UTC
            </span>
        </div>

    </div>
@endsection
