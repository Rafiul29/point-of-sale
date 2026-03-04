@extends('layouts.app')

@section('header', 'Audit Log')

@section('content')
<div class="flex flex-col gap-8 pb-20">

    
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
        <h2 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
            <i class="fas fa-filter text-indigo-500"></i>
            Filter Activity
        </h2>
        <form method="GET" action="{{ route('audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
           
            <div class="space-y-1">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
            </div>
            
            <div class="space-y-1">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Action</label>
                <select name="action" class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
         
            <div class="space-y-1">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Model</label>
                <select name="model" class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                    <option value="">All Models</option>
                    @foreach($models as $model)
                        <option value="{{ $model }}" {{ request('model') === $model ? 'selected' : '' }}>{{ $model }}</option>
                    @endforeach
                </select>
            </div>
           
            <div class="space-y-1">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">User</label>
                <select name="user_id" class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-3 text-sm font-semibold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl px-4 py-3 text-sm font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('audit-logs.index') }}" class="h-full aspect-square flex items-center justify-center bg-slate-100 hover:bg-slate-200 rounded-2xl text-slate-500 transition-all" title="Reset">
                    <i class="fas fa-rotate-left"></i>
                </a>
            </div>
        </form>
        <i class="fas fa-shield-halved absolute right-[-20px] top-[-20px] text-[10rem] opacity-[0.02]"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black tracking-tight text-slate-900">Activity Timeline</h2>
                <p class="text-xs font-medium text-slate-400 mt-0.5">{{ $logs->total() }} records found</p>
            </div>
            <span class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 rounded-2xl px-5 py-2 text-xs font-black uppercase tracking-widest">
                <i class="fas fa-clock-rotate-left"></i> Live Tracking
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/60 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="px-10 py-5">Timestamp</th>
                        <th class="px-6 py-5">User</th>
                        <th class="px-6 py-5">Action</th>
                        <th class="px-6 py-5">Model / ID</th>
                        <th class="px-6 py-5">Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    @php
                        $actionColors = [
                            'sale_created'    => 'emerald',
                            'stock_in'        => 'blue',
                            'price_changed'   => 'amber',
                            'product_created' => 'indigo',
                            'product_updated' => 'violet',
                            'product_deleted' => 'rose',
                        ];
                        $color = $actionColors[$log->action] ?? 'slate';
                    @endphp
                    <tr class="hover:bg-slate-50/40 transition-all">
                        <td class="px-10 py-6">
                            <span class="text-sm font-bold text-slate-800">{{ $log->created_at->format('M d, Y') }}</span>
                            <span class="block text-[10px] font-semibold text-slate-400 mt-0.5">{{ $log->created_at->format('h:i:s A') }}</span>
                        </td>
                        <td class="px-6 py-6">
                            @if($log->user)
                                <div class="flex items-center gap-2.5">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&background=6366f110&color=6366f1&bold=true&size=64" class="h-8 w-8 rounded-lg" alt="">
                                    <div>
                                        <span class="block text-sm font-bold text-slate-800">{{ $log->user->name }}</span>
                                        <span class="block text-[10px] font-semibold text-slate-400">{{ $log->user->role }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center rounded-xl bg-{{ $color }}-50 px-3 py-1.5 text-[10px] font-black text-{{ $color }}-600 uppercase tracking-widest border border-{{ $color }}-100">
                                {{ ucwords(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <span class="text-sm font-bold text-slate-700">{{ $log->auditable_type }}</span>
                            <span class="block text-[10px] text-slate-400 font-mono mt-0.5">#{{ $log->auditable_id }}</span>
                        </td>
                        <td class="px-6 py-6">
                            @if($log->old_values || $log->new_values)
                            <a href="{{ route('audit-logs.show', $log) }}"
                               class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl px-3 py-1.5 transition-all">
                                <i class="fas fa-arrow-up-right-from-square text-[9px]"></i>
                                View Details
                            </a>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="h-20 w-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-5 text-3xl">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">No activity found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-8 border-t border-slate-50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
