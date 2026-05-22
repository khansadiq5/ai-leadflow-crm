@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Support Workspace
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Support Customers
            </h1>

            <p class="text-slate-500 mt-2">
                Customers linked to your assigned support tickets.
            </p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Search Customers
            </h2>

            <p class="text-sm text-slate-500">
                Search support customers by name, phone number or email address.
            </p>
        </div>

        <form method="GET" action="{{ route('support.customers.index') }}" class="px-5 py-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                {{-- SEARCH --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Search Customer
                    </label>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input 
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search customer, phone, email..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 pl-11 pr-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-magnifying-glass mr-2 text-xs"></i>
                        Search
                    </button>

                    @if(request('search'))
                        <a href="{{ route('support.customers.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- CUSTOMERS TABLE --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Customer Records
            </h2>

            <p class="text-sm text-slate-500">
                View customers having tickets assigned to your support workspace.
            </p>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden xl:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="text-left px-5 py-4 font-semibold">Customer</th>
                        <th class="text-left px-5 py-4 font-semibold">Contact</th>
                        <th class="text-left px-5 py-4 font-semibold">Company</th>
                        <th class="text-left px-5 py-4 font-semibold">Total Tickets</th>
                        <th class="text-left px-5 py-4 font-semibold">Open Tickets</th>
                        <th class="text-right px-5 py-4 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="transition hover:bg-slate-50/70">

                            {{-- CUSTOMER --}}
                            <td class="px-5 py-4 min-w-[240px]">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-950 truncate">
                                            {{ $customer->name }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            ID: #{{ $customer->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- CONTACT --}}
                            <td class="px-5 py-4 min-w-[230px]">
                                <p class="font-semibold text-slate-900 truncate">
                                    {{ $customer->phone ?? '-' }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ $customer->email ?? '-' }}
                                </p>
                            </td>

                            {{-- COMPANY --}}
                            <td class="px-5 py-4 min-w-[180px]">
                                <p class="text-slate-700 truncate">
                                    {{ $customer->company_name ?? '-' }}
                                </p>
                            </td>

                            {{-- TOTAL TICKETS --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $customer->total_tickets_count }} Tickets
                                </span>
                            </td>

                            {{-- OPEN TICKETS --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ $customer->open_tickets_count }} Open
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="px-5 py-4">
                                <div class="flex justify-end">
                                    <a href="{{ route('support.customers.show', $customer) }}"
                                       title="View Customer"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <i class="fa-solid fa-headset"></i>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                                        No support customers found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Assigned ticket customers will appear here.
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
            @forelse($customers as $customer)

                <div class="px-5 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="font-semibold text-slate-950">
                                    {{ $customer->name }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    ID: #{{ $customer->id }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 break-all">
                                    {{ $customer->email ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('support.customers.show', $customer) }}"
                           title="View Customer"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950 shrink-0">
                            <i class="fa-regular fa-eye text-sm"></i>
                        </a>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $customer->total_tickets_count }} Tickets
                        </span>

                        <span class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ $customer->open_tickets_count }} Open
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">Phone</p>
                            <p class="mt-1 font-semibold text-slate-900 truncate">
                                {{ $customer->phone ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">Company</p>
                            <p class="mt-1 font-semibold text-slate-900 truncate">
                                {{ $customer->company_name ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto max-w-sm">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                            <i class="fa-solid fa-headset"></i>
                        </div>

                        <h3 class="mt-4 text-base font-semibold text-slate-900">
                            No support customers found
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Assigned ticket customers will appear here.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($customers->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $customers->links() }}
            </div>
        @endif

    </div>

</div>

@endsection