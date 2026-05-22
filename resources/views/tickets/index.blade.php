@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Support Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Support Tickets
            </h1>

            <p class="text-slate-500 mt-2">
                Manage customer issues, support requests, priorities and resolution status.
            </p>
        </div>

        @if(auth()->user()->role !== 'support_agent')
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-solid fa-plus mr-2 text-xs"></i>
                Add Ticket
            </a>
        @endif
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTERS --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Filter Tickets
            </h2>

            <p class="text-sm text-slate-500">
                Search and filter tickets by status, priority, category or assigned agent.
            </p>
        </div>

        <form method="GET" action="{{ route('tickets.index') }}" class="px-5 py-5">

            @if(auth()->user()->role === 'support_agent')
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4 items-end">
            @endif

                {{-- SEARCH --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Search Ticket
                    </label>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input 
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search ticket, customer..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 pl-11 pr-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Status
                    </label>

                    <select 
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Status</option>
                        @foreach(['open','in_progress','resolved','closed'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PRIORITY --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Priority
                    </label>

                    <select 
                        name="priority"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Priority</option>
                        @foreach(['low','medium','high','urgent'] as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Category
                    </label>

                    <select 
                        name="category"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Category</option>
                        @foreach(['technical','billing','general','feature_request','complaint'] as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ASSIGNED AGENT --}}
                @if(auth()->user()->role !== 'support_agent')
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                            Assigned Agent
                        </label>

                        <select 
                            name="assigned_to"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="">All Agents</option>
                            @foreach($supportAgents as $agent)
                                <option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- BUTTONS --}}
                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-filter mr-2 text-xs"></i>
                        Filter
                    </button>

                    @if(request('search') || request('status') || request('priority') || request('category') || request('assigned_to'))
                        <a href="{{ route('tickets.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- TICKETS TABLE --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Ticket Records
            </h2>

            <p class="text-sm text-slate-500">
                View and manage customer support tickets.
            </p>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden xl:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="text-left px-4 py-4 font-semibold">Ticket</th>
                        <th class="text-left px-4 py-4 font-semibold">Customer</th>
                        <th class="text-left px-3 py-4 font-semibold">Category</th>
                        <th class="text-left px-3 py-4 font-semibold">Priority</th>
                        <th class="text-left px-3 py-4 font-semibold">Status</th>
                        <th class="text-left px-3 py-4 font-semibold">Assigned</th>
                        <th class="text-left px-3 py-4 font-semibold">Created</th>
                        <th class="text-right px-8 py-4 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($tickets as $ticket)

                        @php
                            $priorityClass = [
                                'low' => 'bg-slate-100 text-slate-700 border-slate-200',
                                'medium' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'high' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'urgent' => 'bg-red-50 text-red-700 border-red-100',
                            ][$ticket->priority] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                            $statusClass = [
                                'open' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'closed' => 'bg-slate-100 text-slate-700 border-slate-200',
                            ][$ticket->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                            $categoryClass = [
                                'technical' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'billing' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'general' => 'bg-slate-100 text-slate-700 border-slate-200',
                                'feature_request' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'complaint' => 'bg-red-50 text-red-700 border-red-100',
                            ][$ticket->category] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                            $ticketIcon = [
                                'technical' => 'fa-screwdriver-wrench',
                                'billing' => 'fa-credit-card',
                                'general' => 'fa-circle-info',
                                'feature_request' => 'fa-lightbulb',
                                'complaint' => 'fa-triangle-exclamation',
                            ][$ticket->category] ?? 'fa-ticket';
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">

                            {{-- TICKET --}}
                            <td class="px-4 py-4 min-w-[260px]">
                                <div class="flex items-start gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        <i class="fa-solid {{ $ticketIcon }} text-xs"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-950 truncate">
                                            {{ $ticket->subject }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1 truncate">
                                            {{ Str::limit($ticket->description, 70) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- CUSTOMER --}}
                            <td class="px-4 py-4 min-w-[180px]">
                                <p class="font-semibold text-slate-900 truncate">
                                    {{ $ticket->customer->name ?? '-' }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ $ticket->customer->phone ?? '-' }}
                                </p>
                            </td>

                            {{-- CATEGORY --}}
                            <td class="px-3 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $categoryClass }}">
                                    {{ ucwords(str_replace('_', ' ', $ticket->category)) }}
                                </span>
                            </td>

                            {{-- PRIORITY --}}
                            <td class="px-3 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-3 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>

                            {{-- ASSIGNED --}}
                            <td class="px-3 py-4 min-w-[150px] text-slate-700">
                                {{ $ticket->assignedUser->name ?? 'Unassigned' }}
                            </td>

                            {{-- CREATED --}}
                            <td class="px-3 py-4 whitespace-nowrap">
                                <p class="font-semibold text-slate-900">
                                    {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y') }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $ticket->created_at->timezone('Asia/Kolkata')->format('h:i A') }}
                                </p>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-3 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('tickets.show', $ticket) }}"
                                       title="View Ticket"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('tickets.edit', $ticket) }}"
                                       title="Edit Ticket"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                            @csrf
                                            @method('DELETE')

                                            <button 
                                                type="submit"
                                                title="Delete Ticket"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-700 transition hover:bg-red-100"
                                            >
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
                                        <i class="fa-solid fa-ticket"></i>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                                        No tickets found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Try changing filters or add a new support ticket.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE/TABLET CARDS --}}
        <div class="xl:hidden divide-y divide-slate-100">
            @forelse($tickets as $ticket)

                @php
                    $priorityClass = [
                        'low' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'medium' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'high' => 'bg-orange-50 text-orange-700 border-orange-100',
                        'urgent' => 'bg-red-50 text-red-700 border-red-100',
                    ][$ticket->priority] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                    $statusClass = [
                        'open' => 'bg-amber-50 text-amber-700 border-amber-100',
                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'closed' => 'bg-slate-100 text-slate-700 border-slate-200',
                    ][$ticket->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                    $categoryClass = [
                        'technical' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                        'billing' => 'bg-purple-50 text-purple-700 border-purple-100',
                        'general' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'feature_request' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'complaint' => 'bg-red-50 text-red-700 border-red-100',
                    ][$ticket->category] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                    $ticketIcon = [
                        'technical' => 'fa-screwdriver-wrench',
                        'billing' => 'fa-credit-card',
                        'general' => 'fa-circle-info',
                        'feature_request' => 'fa-lightbulb',
                        'complaint' => 'fa-triangle-exclamation',
                    ][$ticket->category] ?? 'fa-ticket';
                @endphp

                <div class="px-5 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $ticketIcon }} text-xs"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="font-semibold text-slate-950">
                                    {{ $ticket->subject }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                    {{ $ticket->description }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('tickets.show', $ticket) }}"
                               title="View Ticket"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </a>

                            <a href="{{ route('tickets.edit', $ticket) }}"
                               title="Edit Ticket"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                <i class="fa-regular fa-pen-to-square text-sm"></i>
                            </a>

                            @if(auth()->user()->role === 'admin')
                                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                    @csrf
                                    @method('DELETE')

                                    <button 
                                        type="submit"
                                        title="Delete Ticket"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-700 transition hover:bg-red-100"
                                    >
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $categoryClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->category)) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">Customer</p>
                            <p class="mt-1 font-semibold text-slate-900 truncate">
                                {{ $ticket->customer->name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">Assigned</p>
                            <p class="mt-1 font-semibold text-slate-900 truncate">
                                {{ $ticket->assignedUser->name ?? 'Unassigned' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">Created</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto max-w-sm">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                            <i class="fa-solid fa-ticket"></i>
                        </div>

                        <h3 class="mt-4 text-base font-semibold text-slate-900">
                            No tickets found
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Try changing filters or add a new support ticket.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($tickets->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $tickets->links() }}
            </div>
        @endif

    </div>

</div>

@endsection