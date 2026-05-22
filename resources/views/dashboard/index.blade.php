@extends('layouts.app')

@section('content')

@if($isSupportAgent ?? false)

<div class="max-w-7xl mx-auto space-y-6">

    {{-- SUPPORT HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Support Dashboard
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                My Support Workspace
            </h1>

            <p class="text-slate-500 mt-2">
                Track assigned tickets, urgent issues and customer support progress.
            </p>
        </div>

        <div class="inline-flex w-fit items-center gap-3 rounded-xl border border-slate-200 bg-white/80 px-4 py-3 shadow-sm">
            <div class="h-9 w-9 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="leading-tight">
                <p class="text-xs text-slate-500">Logged in as</p>
                <p class="text-sm font-semibold text-slate-900">
                    {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}
                </p>
            </div>
        </div>
    </div>

    {{-- SUPPORT STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">My Tickets</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">{{ $myTickets }}</h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Open Tickets</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">{{ $openTickets }}</h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-folder-open text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-red-100 bg-red-50/80 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-red-600">Urgent Tickets</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-red-700 mt-2">{{ $urgentTickets }}</h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Resolved</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">{{ $resolvedTickets }}</h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Closed</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">{{ $closedTickets }}</h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- SUPPORT RECENT TICKETS --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5">
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Recent Assigned Tickets
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Latest customer issues assigned to you
                </p>
            </div>

            <a href="{{ route('tickets.index') }}"
               class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950 shrink-0">
                View All
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="px-5 py-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($recentTickets as $ticket)

                @php
                    $priorityClass = [
                        'low' => 'border-slate-200 bg-slate-50 text-slate-600',
                        'medium' => 'border-blue-100 bg-blue-50 text-blue-700',
                        'high' => 'border-orange-100 bg-orange-50 text-orange-700',
                        'urgent' => 'border-red-100 bg-red-50 text-red-700',
                    ][$ticket->priority] ?? 'border-slate-200 bg-slate-50 text-slate-600';

                    $statusClass = [
                        'open' => 'bg-yellow-50 text-yellow-700',
                        'in_progress' => 'bg-blue-50 text-blue-700',
                        'resolved' => 'bg-green-50 text-green-700',
                        'closed' => 'bg-slate-100 text-slate-700',
                    ][$ticket->status] ?? 'bg-slate-100 text-slate-700';
                @endphp

                <a href="{{ route('tickets.show', $ticket) }}"
                   class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200 block">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-semibold text-sm text-slate-900 truncate">
                                {{ $ticket->subject }}
                            </p>

                            <p class="text-xs text-slate-500 mt-1 truncate">
                                {{ $ticket->customer->name ?? '-' }}
                            </p>
                        </div>

                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold shrink-0 {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>

                        <span class="text-xs text-slate-400">
                            {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y') }}
                        </span>
                    </div>
                </a>

            @empty
                <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-12 text-center">
                    <p class="text-sm text-slate-500">No assigned tickets found.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@else

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                CRM Overview
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Dashboard
            </h1>

            <p class="text-slate-500 mt-2">
                Track leads, customers, deals, follow-ups and sales performance from one place.
            </p>
        </div>

        <div class="inline-flex w-fit items-center gap-3 rounded-xl border border-slate-200 bg-white/80 px-4 py-3 shadow-sm">
            <div class="h-9 w-9 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="leading-tight">
                <p class="text-xs text-slate-500">
                    Logged in as
                </p>

                <p class="text-sm font-semibold text-slate-900">
                    {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}
                </p>
            </div>
        </div>
    </div>

    {{-- MAIN STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        {{ auth()->user()->role === 'sales_executive' ? 'My Leads' : 'Total Leads' }}
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalLeads }}
                    </h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        {{ auth()->user()->role === 'sales_executive' ? 'My Customers' : 'Total Customers' }}
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalCustomers }}
                    </h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        {{ auth()->user()->role === 'sales_executive' ? 'My Deals' : 'Total Deals' }}
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalDeals }}
                    </h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-handshake text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        {{ auth()->user()->role === 'sales_executive' ? 'My Tasks' : 'Total Tasks' }}
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalTasks }}
                    </h3>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-list-check text-sm"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- SECONDARY STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Hot Leads</p>
                    <h3 class="text-2xl font-semibold text-slate-950 mt-2">{{ $hotLeads }}</h3>
                    <p class="text-xs text-slate-400 mt-3">High priority sales leads</p>
                </div>

                <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-fire text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Open Deals</p>
                    <h3 class="text-2xl font-semibold text-slate-950 mt-2">{{ $openDeals }}</h3>
                    <p class="text-xs text-slate-400 mt-3">Deals not won/lost yet</p>
                </div>

                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-briefcase text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Pending Tasks</p>
                    <h3 class="text-2xl font-semibold text-slate-950 mt-2">{{ $pendingTasks }}</h3>
                    <p class="text-xs text-slate-400 mt-3">Pending or in-progress tasks</p>
                </div>

                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-clock text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Won Revenue</p>

                    <h3 class="text-2xl font-semibold text-slate-950 mt-2">
                        ₹{{ number_format($wonRevenue, 2) }}
                    </h3>

                    <p class="text-xs text-slate-400 mt-3">Total value of won deals</p>
                </div>

                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-indian-rupee-sign text-sm"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- TICKET STATS --}}
    @if(auth()->user()->role !== 'sales_executive')
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Total Tickets</p>
                        <h3 class="text-2xl font-semibold text-slate-950 mt-2">{{ $totalTickets }}</h3>
                        <p class="text-xs text-slate-400 mt-3">All support requests</p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Open Tickets</p>
                        <h3 class="text-2xl font-semibold text-slate-950 mt-2">{{ $openTickets }}</h3>
                        <p class="text-xs text-slate-400 mt-3">Open or in progress</p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-folder-open text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-red-100 bg-red-50/80 backdrop-blur px-5 py-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-red-600">Urgent Tickets</p>
                        <h3 class="text-2xl font-semibold text-red-700 mt-2">{{ $urgentTickets }}</h3>
                        <p class="text-xs text-red-500/80 mt-3">Needs fast attention</p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Resolved Tickets</p>
                        <h3 class="text-2xl font-semibold text-slate-950 mt-2">{{ $resolvedTickets }}</h3>
                        <p class="text-xs text-slate-400 mt-3">Solved support cases</p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- FOLLOW-UP CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Today’s Follow-ups</p>

                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $todayFollowups }}
                    </h3>

                    <p class="text-xs text-slate-400 mt-3">
                        Leads scheduled for today
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-calendar-check text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-red-100 bg-red-50/80 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-red-600">Overdue Tasks</p>

                    <h3 class="text-3xl font-semibold tracking-tight text-red-700 mt-2">
                        {{ $overdueTasks }}
                    </h3>

                    <p class="text-xs text-red-500/80 mt-3">
                        Tasks past due date
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- RECENT DATA --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- RECENT LEADS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Recent Leads
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Latest added leads
                    </p>
                </div>

                <a href="{{ route('leads.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950 shrink-0">
                    View All
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="px-5 py-5 space-y-4">
                @forelse($recentLeads as $lead)

                    @php
                        $priorityClass = match($lead->priority) {
                            'hot' => 'border-red-100 bg-red-50 text-red-700',
                            'warm' => 'border-amber-100 bg-amber-50 text-amber-700',
                            'cold' => 'border-slate-200 bg-slate-50 text-slate-600',
                            default => 'border-slate-200 bg-slate-50 text-slate-600'
                        };
                    @endphp

                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                        <div class="min-w-0">
                            <p class="font-semibold text-sm text-slate-900 truncate">
                                {{ $lead->name }}
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $lead->phone }}
                            </p>
                        </div>

                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold shrink-0 {{ $priorityClass }}">
                            {{ ucfirst($lead->priority) }}
                        </span>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                        <p class="text-sm text-slate-500">No leads found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RECENT DEALS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Recent Deals
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Latest sales opportunities
                    </p>
                </div>

                <a href="{{ route('deals.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950 shrink-0">
                    View All
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="px-5 py-5 space-y-4">
                @forelse($recentDeals as $deal)
                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-slate-900 truncate">
                                    {{ $deal->title }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ $deal->customer->name ?? '-' }} • {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                                </p>
                            </div>

                            <p class="text-sm font-semibold text-slate-900 shrink-0">
                                ₹{{ number_format($deal->amount, 0) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                        <p class="text-sm text-slate-500">No deals found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- UPCOMING TASKS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Upcoming Tasks
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Next follow-ups
                    </p>
                </div>

                <a href="{{ route('tasks.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950 shrink-0">
                    View All
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="px-5 py-5 space-y-4">
                @forelse($upcomingTasks as $task)
                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                        <p class="font-semibold text-sm text-slate-900">
                            {{ $task->title }}
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </p>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                        <p class="text-sm text-slate-500">No upcoming tasks.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- RECENT TICKETS --}}
    @if(auth()->user()->role !== 'sales_executive')
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Recent Support Tickets
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Latest customer support requests
                    </p>
                </div>

                <a href="{{ route('tickets.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950 shrink-0">
                    View All
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="px-5 py-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($recentTickets as $ticket)

                    @php
                        $priorityClass = [
                            'low' => 'border-slate-200 bg-slate-50 text-slate-600',
                            'medium' => 'border-blue-100 bg-blue-50 text-blue-700',
                            'high' => 'border-orange-100 bg-orange-50 text-orange-700',
                            'urgent' => 'border-red-100 bg-red-50 text-red-700',
                        ][$ticket->priority] ?? 'border-slate-200 bg-slate-50 text-slate-600';

                        $statusClass = [
                            'open' => 'bg-yellow-50 text-yellow-700',
                            'in_progress' => 'bg-blue-50 text-blue-700',
                            'resolved' => 'bg-green-50 text-green-700',
                            'closed' => 'bg-slate-100 text-slate-700',
                        ][$ticket->status] ?? 'bg-slate-100 text-slate-700';
                    @endphp

                    <a href="{{ route('tickets.show', $ticket) }}"
                       class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200 block">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-slate-900 truncate">
                                    {{ $ticket->subject }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ $ticket->customer->name ?? '-' }}
                                    @if($ticket->assignedUser)
                                        • {{ $ticket->assignedUser->name }}
                                    @endif
                                </p>
                            </div>

                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold shrink-0 {{ $statusClass }}">
                                {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>

                            <span class="text-xs text-slate-400">
                                {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y') }}
                            </span>
                        </div>
                    </a>

                @empty
                    <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-12 text-center">
                        <p class="text-sm text-slate-500">No tickets found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</div>

@endif

@endsection