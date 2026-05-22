@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Sales Pipeline
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Deal Pipeline
            </h1>

            <p class="text-slate-500 mt-2">
                Track deals stage-wise in a clean sales pipeline board.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('deals.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-list mr-2 text-xs"></i>
                Deal List
            </a>

            <a href="{{ route('deals.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-solid fa-plus mr-2 text-xs"></i>
                Add Deal
            </a>
        </div>
    </div>

    <!-- PIPELINE BOARD -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @foreach($stages as $stageKey => $stageLabel)

            @php
                $stageDeals = $deals[$stageKey] ?? collect();
                $stageTotal = $stageDeals->sum('amount');

                $stageTheme = [
                    'new' => [
                        'badge' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'dot' => 'bg-blue-500',
                        'soft' => 'bg-blue-50/50',
                    ],
                    'qualified' => [
                        'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                        'dot' => 'bg-indigo-500',
                        'soft' => 'bg-indigo-50/50',
                    ],
                    'proposal_sent' => [
                        'badge' => 'bg-amber-50 text-amber-700 border-amber-100',
                        'dot' => 'bg-amber-500',
                        'soft' => 'bg-amber-50/50',
                    ],
                    'negotiation' => [
                        'badge' => 'bg-orange-50 text-orange-700 border-orange-100',
                        'dot' => 'bg-orange-500',
                        'soft' => 'bg-orange-50/50',
                    ],
                    'won' => [
                        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'dot' => 'bg-emerald-500',
                        'soft' => 'bg-emerald-50/50',
                    ],
                    'lost' => [
                        'badge' => 'bg-red-50 text-red-700 border-red-100',
                        'dot' => 'bg-red-500',
                        'soft' => 'bg-red-50/50',
                    ],
                ][$stageKey] ?? [
                    'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'dot' => 'bg-slate-500',
                    'soft' => 'bg-slate-50',
                ];
            @endphp

            <!-- STAGE COLUMN -->
            <div class="rounded-[1.4rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                <!-- STAGE HEADER -->
                <div class="px-5 py-4 border-b border-slate-100 {{ $stageTheme['soft'] }}">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="h-2.5 w-2.5 rounded-full {{ $stageTheme['dot'] }} shrink-0"></span>

                            <span class="text-sm font-semibold text-slate-950 truncate">
                                {{ $stageLabel }}
                            </span>
                        </div>

                        <span class="inline-flex items-center justify-center rounded-full bg-white border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">
                            {{ $stageDeals->count() }} deals
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-xl border border-white/80 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400 font-medium">
                                Stage Value
                            </p>

                            <p class="mt-1 text-base font-semibold text-slate-950">
                                ₹{{ number_format($stageTotal, 0) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-white/80 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400 font-medium">
                                Deals
                            </p>

                            <p class="mt-1 text-base font-semibold text-slate-950">
                                {{ $stageDeals->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- DEAL CARDS -->
                <div class="p-4 space-y-3">

                    @forelse($stageDeals as $deal)

                        @php
                            $probabilityColor = $deal->probability >= 70
                                ? 'bg-emerald-500'
                                : ($deal->probability >= 40 ? 'bg-amber-500' : 'bg-slate-400');
                        @endphp

                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm transition hover:border-slate-300 hover:shadow-md">

                            <!-- TOP -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ strtoupper(substr($deal->title, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-sm font-semibold text-slate-950">
                                            {{ $deal->title }}
                                        </h3>

                                        <p class="mt-1 truncate text-xs text-slate-500">
                                            {{ $deal->customer->name ?? 'No customer' }}
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ route('deals.show', $deal) }}"
                                   title="View Deal"
                                   class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-950 hover:text-white hover:border-slate-950">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                            </div>

                            <!-- AMOUNT + CLOSE DATE -->
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 px-3 py-2.5">
                                    <p class="text-[11px] text-slate-400">
                                        Amount
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-950 truncate">
                                        ₹{{ number_format($deal->amount, 0) }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 px-3 py-2.5">
                                    <p class="text-[11px] text-slate-400">
                                        Close
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-950 truncate">
                                        {{ $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- PROBABILITY -->
                            <div class="mt-4">
                                <div class="mb-1.5 flex items-center justify-between">
                                    <span class="text-xs text-slate-400">
                                        Probability
                                    </span>

                                    <span class="text-xs font-semibold text-slate-700">
                                        {{ $deal->probability }}%
                                    </span>
                                </div>

                                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                    <div 
                                        class="h-full rounded-full {{ $probabilityColor }}"
                                        style="width: {{ $deal->probability }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- OWNER -->
                            <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                                <span class="text-xs text-slate-400">
                                    Owner
                                </span>

                                <span class="text-xs font-semibold text-slate-800 truncate max-w-[180px]">
                                    {{ $deal->assignedUser->name ?? 'Unassigned' }}
                                </span>
                            </div>

                        </div>

                    @empty

                        <div class="flex min-h-[150px] items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white/60">
                            <div class="text-center px-4">
                                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>

                                <p class="text-sm font-semibold text-slate-500">
                                    No deals
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Empty stage
                                </p>
                            </div>
                        </div>

                    @endforelse

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection