@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Customer Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Customers
            </h1>

            <p class="text-slate-500 mt-2">
                Manage converted leads, active customers and customer profiles.
            </p>
        </div>

        <a href="{{ route('customers.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
            <i class="fa-solid fa-plus mr-2 text-xs"></i>
            Add Customer
        </a>
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
                Filter Customers
            </h2>
            <p class="text-sm text-slate-500">
                Search and filter customers by type or account status.
            </p>
        </div>

        <form method="GET" action="{{ route('customers.index') }}" class="px-5 py-5">
            <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_1fr_1fr_auto] gap-4 items-end">

                <div>
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
                            placeholder="Search by name, email, phone..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 pl-11 pr-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Type
                    </label>

                    <select 
                        name="type" 
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Types</option>
                        @foreach(['individual','company','vip','regular'] as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Status
                    </label>

                    <select 
                        name="status" 
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Status</option>
                        @foreach(['active','inactive'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
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

                    @if(request('search') || request('type') || request('status'))
                        <a href="{{ route('customers.index') }}"
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

        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="text-base font-semibold text-slate-950">
                    Customer Records
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    View, edit and manage all customer profiles.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="text-left px-5 py-4 font-semibold">Customer</th>
                        <th class="text-left px-5 py-4 font-semibold">Phone</th>
                        <th class="text-left px-5 py-4 font-semibold">Company</th>
                        <th class="text-left px-5 py-4 font-semibold">City</th>
                        <th class="text-left px-5 py-4 font-semibold">Type</th>
                        <th class="text-left px-5 py-4 font-semibold">Status</th>
                        <th class="text-left px-5 py-4 font-semibold">Assigned</th>
                        <th class="text-right px-5 py-4 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)

                        @php
                            $statusClass = $customer->status === 'active'
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                : 'bg-slate-100 text-slate-700 border-slate-200';

                            $typeClass = [
                                'individual' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'company' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'vip' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'regular' => 'bg-slate-100 text-slate-700 border-slate-200',
                            ][$customer->type] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4 min-w-[240px]">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-950 truncate">
                                            {{ $customer->name }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1 truncate">
                                            {{ $customer->email ?? 'No email' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-slate-700">
                                {{ $customer->phone }}
                            </td>

                            <td class="px-5 py-4 min-w-[160px] text-slate-700">
                                {{ $customer->company_name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-slate-700">
                                {{ $customer->city ?? '-' }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $typeClass }}">
                                    {{ ucfirst($customer->type) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 min-w-[150px] text-slate-700">
                                {{ $customer->assignedUser->name ?? 'Unassigned' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('customers.show', $customer) }}"
                                       title="View Customer"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('customers.edit', $customer) }}"
                                       title="Edit Customer"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                            @csrf
                                            @method('DELETE')

                                            <button 
                                                type="submit"
                                                title="Delete Customer"
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
                                        <i class="fa-solid fa-users"></i>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                                        No customers found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Try changing filters or add a new customer to get started.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-5 py-4">
            {{ $customers->links() }}
        </div>
    </div>

</div>

@endsection