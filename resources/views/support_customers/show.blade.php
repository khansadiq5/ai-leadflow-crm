@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Support Customer
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950 truncate">
                {{ $customer->name }}
            </h1>

            <p class="text-slate-500 mt-2">
                Support profile and ticket history for this customer.
            </p>
        </div>

        <a href="{{ route('support.customers.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
            Back
        </a>
    </div>

    {{-- CUSTOMER OVERVIEW --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4 min-w-0">
                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-lg font-semibold shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <h2 class="text-lg font-semibold text-slate-950 truncate">
                        {{ $customer->name }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1 truncate">
                        {{ $customer->company_name ?? 'No company added' }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                            ID: #{{ $customer->id }}
                        </span>

                        <span class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ $openTickets }} Open Tickets
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[560px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Phone
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $customer->phone ?? '-' }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Email
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $customer->email ?? '-' }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Location
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $customer->city ?? '-' }}{{ $customer->state ? ', '.$customer->state : '' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        Total Tickets
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-slate-950 mt-2">
                        {{ $totalTickets }}
                    </h3>

                    <p class="text-xs text-slate-400 mt-3">
                        All linked support tickets
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-ticket text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-blue-100 bg-blue-50/70 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-blue-700">
                        Open Tickets
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-blue-700 mt-2">
                        {{ $openTickets }}
                    </h3>

                    <p class="text-xs text-blue-600/70 mt-3">
                        Waiting for resolution
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-clock text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-emerald-100 bg-emerald-50/70 backdrop-blur px-5 py-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-emerald-700">
                        Resolved / Closed
                    </p>

                    <h3 class="text-3xl font-semibold tracking-tight text-emerald-700 mt-2">
                        {{ $resolvedTickets }}
                    </h3>

                    <p class="text-xs text-emerald-600/70 mt-3">
                        Completed support cases
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CUSTOMER DETAILS --}}
        <div class="lg:col-span-1 space-y-6">

            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Customer Details
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Basic customer contact and company information.
                </p>

                <div class="mt-5 space-y-4 text-sm">

                    <div class="rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Name
                        </p>
                        <p class="mt-1 font-semibold text-slate-900 truncate">
                            {{ $customer->name }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Phone
                        </p>
                        <p class="mt-1 font-semibold text-slate-900 truncate">
                            {{ $customer->phone ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Email
                        </p>
                        <p class="mt-1 font-semibold text-slate-900 break-all">
                            {{ $customer->email ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Company
                        </p>
                        <p class="mt-1 font-semibold text-slate-900 truncate">
                            {{ $customer->company_name ?? '-' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                        <div class="rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                City
                            </p>
                            <p class="mt-1 font-semibold text-slate-900 truncate">
                                {{ $customer->city ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                State
                            </p>
                            <p class="mt-1 font-semibold text-slate-900 truncate">
                                {{ $customer->state ?? '-' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ASSIGNED TICKETS --}}
        <div class="lg:col-span-2">

            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Assigned Tickets
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Tickets assigned to your support workspace for this customer.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $totalTickets }} Tickets
                    </span>
                </div>

                <div class="px-5 sm:px-6 py-6">
                    <div class="space-y-4">

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

                                $ticketIcon = [
                                    'low' => 'fa-circle-info',
                                    'medium' => 'fa-ticket',
                                    'high' => 'fa-triangle-exclamation',
                                    'urgent' => 'fa-fire',
                                ][$ticket->priority] ?? 'fa-ticket';
                            @endphp

                            <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                                            <i class="fa-solid {{ $ticketIcon }} text-xs"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-950 truncate">
                                                {{ $ticket->subject }}
                                            </p>

                                            <p class="text-sm text-slate-500 mt-1 leading-6">
                                                {{ \Illuminate\Support\Str::limit($ticket->description, 90) }}
                                            </p>

                                            <div class="flex flex-wrap gap-2 mt-3">
                                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>

                                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('tickets.show', $ticket) }}"
                                       class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99] shrink-0">
                                        Open Ticket
                                        <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                    </a>

                                </div>
                            </div>

                        @empty

                            <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-12 text-center">
                                <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <i class="fa-solid fa-ticket"></i>
                                </div>

                                <h3 class="mt-4 text-base font-semibold text-slate-900">
                                    No assigned tickets found
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Assigned support tickets for this customer will appear here.
                                </p>
                            </div>

                        @endforelse

                    </div>
                </div>

                @if($tickets->hasPages())
                    <div class="border-t border-slate-100 px-5 sm:px-6 py-4">
                        {{ $tickets->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection