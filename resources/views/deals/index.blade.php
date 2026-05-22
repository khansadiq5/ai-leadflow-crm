@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Sales Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Deals
            </h1>

            <p class="text-slate-500 mt-2">
                Track sales opportunities, deal stages and expected revenue.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('deals.pipeline') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-diagram-project mr-2 text-xs"></i>
                Pipeline
            </a>

            <a href="{{ route('deals.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-solid fa-plus mr-2 text-xs"></i>
                Add Deal
            </a>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- FILTERS -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Filter Deals
            </h2>
            <p class="text-sm text-slate-500">
                Search and filter deals by stage or assigned team member.
            </p>
        </div>

        <form method="GET" action="{{ route('deals.index') }}" class="px-5 py-5">
            <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_1fr_1fr_auto] gap-4 items-end">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Search Deal
                    </label>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search deal or customer..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 pl-11 pr-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Stage
                    </label>

                    <select 
                        name="stage"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Stages</option>
                        @foreach(['new','qualified','proposal_sent','negotiation','won','lost'] as $stage)
                            <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $stage)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Assigned User
                    </label>

                    <select 
                        name="assigned_to"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Assigned Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-filter mr-2 text-xs"></i>
                        Filter
                    </button>

                    @if(request('search') || request('stage') || request('assigned_to'))
                        <a href="{{ route('deals.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Deal Records
            </h2>
            <p class="text-sm text-slate-500">
                View, edit and manage all sales opportunities.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="text-left px-5 py-4 font-semibold">Deal</th>
                        <th class="text-left px-5 py-4 font-semibold">Customer</th>
                        <th class="text-left px-5 py-4 font-semibold">Amount</th>
                        <th class="text-left px-5 py-4 font-semibold">Stage</th>
                        <th class="text-left px-5 py-4 font-semibold">Probability</th>
                        <th class="text-left px-5 py-4 font-semibold">Expected Close</th>
                        <th class="text-left px-5 py-4 font-semibold">Assigned</th>
                        <th class="text-right px-5 py-4 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($deals as $deal)

                        @php
                            $stageClass = [
                                'new' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'qualified' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'proposal_sent' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'negotiation' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'won' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'lost' => 'bg-red-50 text-red-700 border-red-100',
                            ][$deal->stage] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4 min-w-[240px]">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ strtoupper(substr($deal->title, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-950 truncate">
                                            {{ $deal->title }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1 truncate">
                                            Created by {{ $deal->createdBy->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 min-w-[150px]">
                                <p class="font-semibold text-slate-900 truncate">
                                    {{ $deal->customer->name ?? '-' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ $deal->customer->company_name ?? 'No company' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-semibold text-slate-950">
                                    ₹{{ number_format($deal->amount) }}
                                </p>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $stageClass }}">
                                    {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                                </span>
                            </td>

                            <td class="px-10 py-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-800">
                                    {{ $deal->probability }}%
                                </span>
                            </td>

                            <td class="px-7 py-4 whitespace-nowrap text-slate-700">
                                {{ $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('d M Y') : '-' }}
                            </td>

                            <td class="px-5 py-4 min-w-[150px] text-slate-700">
                                {{ $deal->assignedUser->name ?? 'Unassigned' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('deals.show', $deal) }}"
                                       title="View Deal"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('deals.edit', $deal) }}"
                                       title="Edit Deal"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('deals.destroy', $deal) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this deal?')">
                                            @csrf
                                            @method('DELETE')

                                            <button 
                                                type="submit"
                                                title="Delete Deal"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-700 transition hover:bg-red-100">
                                                <i class="fa-regular fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <i class="fa-solid fa-handshake"></i>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                                        No deals found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Try changing filters or add a new deal to start tracking opportunities.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-5 py-4">
            {{ $deals->links() }}
        </div>
    </div>

</div>

@endsection