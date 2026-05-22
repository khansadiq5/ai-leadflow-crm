@extends('layouts.app')

@section('content')

@php
    $leadConversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100) : 0;
    $dealWinRate = $totalDeals > 0 ? round(($wonDeals / $totalDeals) * 100) : 0;
    $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    $ticketResolutionRate = $totalTickets > 0 ? round((($resolvedTickets + $closedTickets) / $totalTickets) * 100) : 0;
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Reports & Analytics
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                CRM Performance Reports
            </h1>

            <p class="text-slate-500 mt-2">
                Track sales performance, revenue, follow-ups and support activity in one place.
            </p>
        </div>

        <div class="inline-flex w-fit items-center gap-3 rounded-xl border border-slate-200 bg-white/80 px-4 py-3 shadow-sm">
            <div class="h-9 w-9 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>

            <div class="leading-tight">
                <p class="text-xs text-slate-500">Report Period</p>
                <p class="text-sm font-semibold text-slate-900">
                    {{ $fromDate->format('d M Y') }} - {{ $toDate->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Filter Reports
            </h2>

            <p class="text-sm text-slate-500">
                Select a date range to analyze CRM performance.
            </p>
        </div>

        <form method="GET" action="{{ route('reports.index') }}" class="px-5 py-5">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-end">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        From Date
                    </label>

                    <input 
                        type="date"
                        id="from_date"
                        name="from_date"
                        value="{{ request('from_date', $fromDate->format('Y-m-d')) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        To Date
                    </label>

                    <input 
                        type="date"
                        id="to_date"
                        name="to_date"
                        value="{{ request('to_date', $toDate->format('Y-m-d')) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div class="xl:col-span-2 flex items-end justify-start gap-3">
                    <button 
                        type="submit"
                        class="inline-flex h-12 w-fit items-center justify-center rounded-xl bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-filter mr-2 text-xs"></i>
                        Apply Filter
                    </button>

                    @if(request('from_date') || request('to_date'))
                        <a href="{{ route('reports.index') }}"
                        title="Clear Filter"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- EXPORT REPORTS --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Export Reports
            </h2>

            <p class="text-sm text-slate-500">
                Download CSV reports for the selected date range.
            </p>
        </div>

        <div class="px-5 py-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

                <a href="#"
                    data-export-url="{{ route('reports.export.leads') }}"
                    class="export-btn group rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200 hover:shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-plus text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-950">
                                Leads CSV
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Lead report export
                            </p>
                        </div>

                        <i class="fa-solid fa-download ml-auto text-xs text-slate-400 transition group-hover:text-slate-950"></i>
                    </div>
                </a>

                <a href="#"
                    data-export-url="{{ route('reports.export.deals') }}"
                    class="export-btn group rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200 hover:shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-handshake text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-950">
                                Deals CSV
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Revenue report export
                            </p>
                        </div>

                        <i class="fa-solid fa-download ml-auto text-xs text-slate-400 transition group-hover:text-slate-950"></i>
                    </div>
                </a>

                <a href="#"
                    data-export-url="{{ route('reports.export.tasks') }}"
                    class="export-btn group rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200 hover:shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-list-check text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-950">
                                Tasks CSV
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Follow-up report export
                            </p>
                        </div>

                        <i class="fa-solid fa-download ml-auto text-xs text-slate-400 transition group-hover:text-slate-950"></i>
                    </div>
                </a>

                <a href="#"
                    data-export-url="{{ route('reports.export.tickets') }}"
                    class="export-btn group rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200 hover:shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-headset text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-950">
                                Tickets CSV
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Support report export
                            </p>
                        </div>

                        <i class="fa-solid fa-download ml-auto text-xs text-slate-400 transition group-hover:text-slate-950"></i>
                    </div>
                </a>

                <a href="#"
                data-export-url="{{ route('reports.export.pdf') }}"
                class="export-btn group rounded-2xl border border-red-100 bg-red-50/70 px-4 py-4 transition hover:bg-red-50 hover:border-red-200 hover:shadow-sm">
                    <div class="flex items-center gap-3">

                        <div class="h-11 w-11 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-file-pdf text-sm"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-950">
                                PDF Report
                            </p>

                            <p class="text-xs text-slate-500 mt-1 truncate">
                                Full report export
                            </p>
                        </div>

                        <div class="h-8 w-8 rounded-lg bg-white/80 border border-red-100 text-red-600 flex items-center justify-center shrink-0 transition group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600">
                            <i class="fa-solid fa-download text-xs"></i>
                        </div>

                    </div>
                </a>

            </div>
        </div>

    </div>

    {{-- TOP SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Total Leads</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalLeads }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-3">{{ $convertedLeads }} converted leads</p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span>Conversion</span>
                    <span class="font-semibold">{{ $leadConversionRate }}%</span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-slate-950" style="width: {{ $leadConversionRate }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Won Revenue</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        ₹{{ number_format($wonRevenue, 0) }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-3">{{ $wonDeals }} won deals</p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-indian-rupee-sign text-sm"></i>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span>Win Rate</span>
                    <span class="font-semibold">{{ $dealWinRate }}%</span>
                </div>
                <div class="h-2 rounded-full bg-emerald-100 overflow-hidden">
                    <div class="h-full rounded-full bg-emerald-600" style="width: {{ $dealWinRate }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Completed Tasks</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $completedTasks }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-3">{{ $overdueTasks }} overdue tasks</p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-list-check text-sm"></i>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span>Completion</span>
                    <span class="font-semibold">{{ $taskCompletionRate }}%</span>
                </div>
                <div class="h-2 rounded-full bg-blue-100 overflow-hidden">
                    <div class="h-full rounded-full bg-blue-600" style="width: {{ $taskCompletionRate }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Support Tickets</p>
                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalTickets }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-3">{{ $urgentTickets }} urgent tickets</p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-sm"></i>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span>Resolved</span>
                    <span class="font-semibold">{{ $ticketResolutionRate }}%</span>
                </div>
                <div class="h-2 rounded-full bg-red-100 overflow-hidden">
                    <div class="h-full rounded-full bg-red-600" style="width: {{ $ticketResolutionRate }}%"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- PERFORMANCE VISUAL STRIP --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="rounded-[1.5rem] border border-red-100 bg-red-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600">Hot Leads</p>
                    <h3 class="text-2xl font-semibold text-red-700 mt-2">{{ $hotLeads }}</h3>
                </div>
                <i class="fa-solid fa-fire text-red-500 text-xl"></i>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-blue-100 bg-blue-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600">Open Deals</p>
                    <h3 class="text-2xl font-semibold text-blue-700 mt-2">{{ $openDeals }}</h3>
                </div>
                <i class="fa-solid fa-briefcase text-blue-500 text-xl"></i>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-amber-100 bg-amber-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-amber-600">Pending Tasks</p>
                    <h3 class="text-2xl font-semibold text-amber-700 mt-2">{{ $pendingTasks }}</h3>
                </div>
                <i class="fa-regular fa-clock text-amber-500 text-xl"></i>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-purple-100 bg-purple-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600">Pipeline Revenue</p>
                    <h3 class="text-2xl font-semibold text-purple-700 mt-2">
                        ₹{{ number_format($pipelineRevenue, 0) }}
                    </h3>
                </div>
                <i class="fa-solid fa-chart-line text-purple-500 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm p-5">
            <div class="mb-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Lead Status Overview
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Distribution of leads by current status.
                </p>
            </div>

            <div class="h-72">
                <canvas id="leadStatusChart"></canvas>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm p-5">
            <div class="mb-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Deal Pipeline Overview
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Deals grouped by sales pipeline stage.
                </p>
            </div>

            <div class="h-72">
                <canvas id="dealStageChart"></canvas>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm p-5">
            <div class="mb-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Task Performance Overview
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Follow-up tasks by completion status.
                </p>
            </div>

            <div class="h-72">
                <canvas id="taskStatusChart"></canvas>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm p-5">
            <div class="mb-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Ticket Status Overview
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Support tickets grouped by resolution status.
                </p>
            </div>

            <div class="h-72">
                <canvas id="ticketStatusChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ANALYTICS SECTIONS --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- LEAD ANALYTICS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Lead Analytics</h2>
                <p class="text-sm text-slate-500 mt-1">Lead quality and conversion overview.</p>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-red-100 bg-red-50/70 p-4">
                    <p class="text-sm text-red-600">Hot Leads</p>
                    <h3 class="text-2xl font-semibold mt-2 text-red-700">{{ $hotLeads }}</h3>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4">
                    <p class="text-sm text-amber-600">Warm Leads</p>
                    <h3 class="text-2xl font-semibold mt-2 text-amber-700">{{ $warmLeads }}</h3>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white/70 p-4">
                    <p class="text-sm text-slate-500">Cold Leads</p>
                    <h3 class="text-2xl font-semibold mt-2 text-slate-900">{{ $coldLeads }}</h3>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                    <p class="text-sm text-emerald-600">Converted Leads</p>
                    <h3 class="text-2xl font-semibold mt-2 text-emerald-700">{{ $convertedLeads }}</h3>
                </div>
            </div>

            <div class="px-5 pb-5">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Lead Status Breakdown</h3>

                <div class="space-y-3">
                    @foreach($leadStatusCounts as $status => $count)
                        @php
                            $percent = $totalLeads > 0 ? round(($count / $totalLeads) * 100) : 0;
                        @endphp

                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-slate-600">
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </span>

                                <span class="text-sm font-semibold text-slate-950">
                                    {{ $count }}
                                </span>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-slate-950" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- CUSTOMER ANALYTICS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Customer Analytics</h2>
                <p class="text-sm text-slate-500 mt-1">Customer status summary and health insight.</p>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-white/70 p-4">
                    <p class="text-sm text-slate-500">Total Customers</p>
                    <h3 class="text-2xl font-semibold mt-2">{{ $totalCustomers }}</h3>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                    <p class="text-sm text-emerald-600">Active Customers</p>
                    <h3 class="text-2xl font-semibold mt-2 text-emerald-700">{{ $activeCustomers }}</h3>
                </div>

                <div class="rounded-2xl border border-red-100 bg-red-50/70 p-4">
                    <p class="text-sm text-red-600">Inactive Customers</p>
                    <h3 class="text-2xl font-semibold mt-2 text-red-700">{{ $inactiveCustomers }}</h3>
                </div>
            </div>

            <div class="px-5 pb-5">
                <div class="rounded-2xl border border-slate-100 bg-white/70 p-5">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-heart-pulse text-sm"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-950">Customer Health Insight</h3>
                            <p class="text-sm text-slate-500 mt-2 leading-6">
                                Active customers are useful for deal tracking and support follow-ups. Inactive customers can be reviewed for re-engagement campaigns.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DEAL ANALYTICS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Deal & Revenue Analytics</h2>
                <p class="text-sm text-slate-500 mt-1">Pipeline, won deals and revenue performance.</p>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-white/70 p-4">
                    <p class="text-sm text-slate-500">Total Deals</p>
                    <h3 class="text-2xl font-semibold mt-2">{{ $totalDeals }}</h3>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                    <p class="text-sm text-blue-600">Open Deals</p>
                    <h3 class="text-2xl font-semibold mt-2 text-blue-700">{{ $openDeals }}</h3>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                    <p class="text-sm text-emerald-600">Won Deals</p>
                    <h3 class="text-2xl font-semibold mt-2 text-emerald-700">{{ $wonDeals }}</h3>
                </div>

                <div class="rounded-2xl border border-red-100 bg-red-50/70 p-4">
                    <p class="text-sm text-red-600">Lost Deals</p>
                    <h3 class="text-2xl font-semibold mt-2 text-red-700">{{ $lostDeals }}</h3>
                </div>
            </div>

            <div class="px-5 pb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/80 p-4">
                    <p class="text-sm text-emerald-700">Won Revenue</p>
                    <h3 class="text-2xl font-semibold mt-2 text-emerald-700">
                        ₹{{ number_format($wonRevenue, 2) }}
                    </h3>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50/80 p-4">
                    <p class="text-sm text-blue-700">Pipeline Revenue</p>
                    <h3 class="text-2xl font-semibold mt-2 text-blue-700">
                        ₹{{ number_format($pipelineRevenue, 2) }}
                    </h3>
                </div>
            </div>

            <div class="px-5 pb-5">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Deal Stage Breakdown</h3>

                <div class="space-y-3">
                    @foreach($dealStageCounts as $stage => $count)
                        @php
                            $percent = $totalDeals > 0 ? round(($count / $totalDeals) * 100) : 0;
                        @endphp

                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-slate-600">
                                    {{ ucwords(str_replace('_', ' ', $stage)) }}
                                </span>

                                <span class="text-sm font-semibold text-slate-950">
                                    {{ $count }}
                                </span>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-blue-600" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TASK ANALYTICS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Task Performance</h2>
                <p class="text-sm text-slate-500 mt-1">Follow-up and activity completion status.</p>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-white/70 p-4">
                    <p class="text-sm text-slate-500">Total Tasks</p>
                    <h3 class="text-2xl font-semibold mt-2">{{ $totalTasks }}</h3>
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                    <p class="text-sm text-emerald-600">Completed</p>
                    <h3 class="text-2xl font-semibold mt-2 text-emerald-700">{{ $completedTasks }}</h3>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4">
                    <p class="text-sm text-amber-600">Pending</p>
                    <h3 class="text-2xl font-semibold mt-2 text-amber-700">{{ $pendingTasks }}</h3>
                </div>

                <div class="rounded-2xl border border-red-100 bg-red-50/80 p-4">
                    <p class="text-sm text-red-600">Overdue</p>
                    <h3 class="text-2xl font-semibold mt-2 text-red-700">{{ $overdueTasks }}</h3>
                </div>
            </div>

            <div class="px-5 pb-5">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Task Status Breakdown</h3>

                <div class="space-y-3">
                    @foreach($taskStatusCounts as $status => $count)
                        @php
                            $percent = $totalTasks > 0 ? round(($count / $totalTasks) * 100) : 0;
                        @endphp

                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-slate-600">
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </span>

                                <span class="text-sm font-semibold text-slate-950">
                                    {{ $count }}
                                </span>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-emerald-600" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- TICKET REPORT --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 px-5 py-5">
            <h2 class="text-lg font-semibold tracking-tight text-slate-950">Ticket Analytics</h2>
            <p class="text-sm text-slate-500 mt-1">Support workload and ticket resolution overview.</p>
        </div>

        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
            <div class="rounded-2xl border border-slate-100 bg-white/70 p-4">
                <p class="text-sm text-slate-500">Total Tickets</p>
                <h3 class="text-2xl font-semibold mt-2">{{ $totalTickets }}</h3>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-amber-50/80 p-4">
                <p class="text-sm text-amber-700">Open</p>
                <h3 class="text-2xl font-semibold mt-2 text-amber-700">{{ $openTickets }}</h3>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50/80 p-4">
                <p class="text-sm text-blue-700">In Progress</p>
                <h3 class="text-2xl font-semibold mt-2 text-blue-700">{{ $inProgressTickets }}</h3>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/80 p-4">
                <p class="text-sm text-emerald-700">Resolved</p>
                <h3 class="text-2xl font-semibold mt-2 text-emerald-700">{{ $resolvedTickets }}</h3>
            </div>

            <div class="rounded-2xl border border-red-100 bg-red-50/80 p-4">
                <p class="text-sm text-red-600">Urgent</p>
                <h3 class="text-2xl font-semibold mt-2 text-red-700">{{ $urgentTickets }}</h3>
            </div>
        </div>

        <div class="px-5 pb-5 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Ticket Status Breakdown</h3>

                <div class="space-y-3">
                    @foreach($ticketStatusCounts as $status => $count)
                        @php
                            $percent = $totalTickets > 0 ? round(($count / $totalTickets) * 100) : 0;
                        @endphp

                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-slate-600">
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </span>

                                <span class="text-sm font-semibold text-slate-950">
                                    {{ $count }}
                                </span>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-red-600" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Ticket Priority Breakdown</h3>

                <div class="space-y-3">
                    @foreach($ticketPriorityCounts as $priority => $count)
                        @php
                            $percent = $totalTickets > 0 ? round(($count / $totalTickets) * 100) : 0;
                        @endphp

                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-slate-600">
                                    {{ ucfirst($priority) }}
                                </span>

                                <span class="text-sm font-semibold text-slate-950">
                                    {{ $count }}
                                </span>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-slate-950" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- TEAM PERFORMANCE --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Sales Team Performance</h2>
                <p class="text-sm text-slate-500 mt-1">Assigned leads and task completion by user.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50/80 text-slate-500">
                        <tr>
                            <th class="text-left px-5 py-4 font-semibold">User</th>
                            <th class="text-left px-5 py-4 font-semibold">Leads</th>
                            <th class="text-left px-5 py-4 font-semibold">Tasks</th>
                            <th class="text-left px-5 py-4 font-semibold">Completed</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($salesUsers as $user)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4 min-w-[180px]">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ ucwords(str_replace('_', ' ', $user->role)) }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-semibold">{{ $user->assigned_leads_count }}</td>
                                <td class="px-5 py-4 font-semibold">{{ $user->assigned_tasks_count }}</td>
                                <td class="px-5 py-4 font-semibold text-emerald-700">{{ $user->completed_tasks_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                    No sales users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Support Team Performance</h2>
                <p class="text-sm text-slate-500 mt-1">Assigned and resolved tickets by support agent.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50/80 text-slate-500">
                        <tr>
                            <th class="text-left px-5 py-4 font-semibold">Agent</th>
                            <th class="text-left px-5 py-4 font-semibold">Tickets</th>
                            <th class="text-left px-5 py-4 font-semibold">Resolved</th>
                            <th class="text-left px-5 py-4 font-semibold">Rate</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($supportUsers as $user)
                            @php
                                $rate = $user->assigned_tickets_count > 0
                                    ? round(($user->resolved_tickets_count / $user->assigned_tickets_count) * 100)
                                    : 0;
                            @endphp

                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4 min-w-[180px]">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-500">Support Agent</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-semibold">{{ $user->assigned_tickets_count }}</td>
                                <td class="px-5 py-4 font-semibold text-emerald-700">{{ $user->resolved_tickets_count }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                    No support agents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- IMPORTANT RECORDS --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Recent Won Deals</h2>
                <p class="text-sm text-slate-500 mt-1">Recently closed sales.</p>
            </div>

            <div class="p-5 space-y-4">
                @forelse($recentWonDeals as $deal)
                    <a href="{{ route('deals.show', $deal) }}"
                       class="block rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-slate-900 truncate">{{ $deal->title }}</p>
                                <p class="text-xs text-slate-500 mt-1 truncate">{{ $deal->customer->name ?? '-' }}</p>
                            </div>

                            <p class="text-sm font-semibold text-emerald-700 shrink-0">
                                ₹{{ number_format($deal->amount, 0) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                        <p class="text-sm text-slate-500">No won deals found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">Urgent Tickets</h2>
                <p class="text-sm text-slate-500 mt-1">High attention support cases.</p>
            </div>

            <div class="p-5 space-y-4">
                @forelse($recentUrgentTickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}"
                       class="block rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                        <p class="font-semibold text-sm text-slate-900 truncate">{{ $ticket->subject }}</p>
                        <p class="text-xs text-slate-500 mt-1 truncate">
                            {{ $ticket->customer->name ?? '-' }}
                            @if($ticket->assignedUser)
                                • {{ $ticket->assignedUser->name }}
                            @endif
                        </p>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                        <p class="text-sm text-slate-500">No urgent tickets found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-red-100 bg-red-50/70 backdrop-blur shadow-sm overflow-hidden">
            <div class="border-b border-red-100 px-5 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-red-700">Overdue Tasks</h2>
                <p class="text-sm text-red-500 mt-1">Tasks that need immediate follow-up.</p>
            </div>

            <div class="p-5 space-y-4">
                @forelse($recentOverdueTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}"
                       class="block rounded-2xl border border-red-100 bg-white/80 px-4 py-4 transition hover:bg-white">
                        <p class="font-semibold text-sm text-slate-900 truncate">{{ $task->title }}</p>
                        <p class="text-xs text-red-500 mt-1">
                            Due {{ $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </p>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-red-200 bg-white/60 px-5 py-10 text-center">
                        <p class="text-sm text-red-500">No overdue tasks found.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 18
                }
            }
        }
    };

    const barOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    };

    new Chart(document.getElementById('leadStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_map(fn($key) => ucwords(str_replace('_', ' ', $key)), array_keys($leadStatusCounts))) !!},
            datasets: [{
                data: {!! json_encode(array_values($leadStatusCounts)) !!},
                backgroundColor: ['#0f172a', '#2563eb', '#f59e0b', '#10b981', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('dealStageChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_map(fn($key) => ucwords(str_replace('_', ' ', $key)), array_keys($dealStageCounts))) !!},
            datasets: [{
                data: {!! json_encode(array_values($dealStageCounts)) !!},
                backgroundColor: '#0f172a',
                borderRadius: 10
            }]
        },
        options: barOptions
    });

    new Chart(document.getElementById('taskStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_map(fn($key) => ucwords(str_replace('_', ' ', $key)), array_keys($taskStatusCounts))) !!},
            datasets: [{
                data: {!! json_encode(array_values($taskStatusCounts)) !!},
                backgroundColor: ['#f59e0b', '#2563eb', '#10b981', '#64748b'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('ticketStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_map(fn($key) => ucwords(str_replace('_', ' ', $key)), array_keys($ticketStatusCounts))) !!},
            datasets: [{
                data: {!! json_encode(array_values($ticketStatusCounts)) !!},
                backgroundColor: ['#f59e0b', '#2563eb', '#10b981', '#64748b'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });
</script>

<script>
    document.querySelectorAll('.export-btn').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;
            const baseUrl = this.dataset.exportUrl;

            const params = new URLSearchParams();

            if (fromDate) {
                params.append('from_date', fromDate);
            }

            if (toDate) {
                params.append('to_date', toDate);
            }

            window.location.href = baseUrl + '?' + params.toString();
        });
    });
</script>

@endsection