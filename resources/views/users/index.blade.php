@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Team Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Users
            </h1>

            <p class="text-slate-500 mt-2">
                Manage CRM users, roles, permissions and account status.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
            <i class="fa-solid fa-plus mr-2 text-xs"></i>
            Add User
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTERS --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                Filter Users
            </h2>

            <p class="text-sm text-slate-500">
                Search and filter CRM users by role or account status.
            </p>
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="px-5 py-5">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-end">

                {{-- SEARCH --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Search User
                    </label>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input 
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search name or email..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 pl-11 pr-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>
                </div>

                {{-- ROLE --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Role
                    </label>

                    <select 
                        name="role"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Roles</option>
                        @foreach(['admin','manager','sales_executive','support_agent'] as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
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
                        @foreach(['active','inactive'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BUTTONS --}}
                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-filter mr-2 text-xs"></i>
                        Filter
                    </button>

                    @if(request('search') || request('role') || request('status'))
                        <a href="{{ route('users.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- USERS TABLE --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-950">
                User Records
            </h2>

            <p class="text-sm text-slate-500">
                View and manage CRM user accounts.
            </p>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden xl:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="text-left px-5 py-4 font-semibold">User</th>
                        <th class="text-left px-5 py-4 font-semibold">Email</th>
                        <th class="text-left px-5 py-4 font-semibold">Role</th>
                        <th class="text-left px-5 py-4 font-semibold">Status</th>
                        <th class="text-left px-5 py-4 font-semibold">Created</th>
                        <th class="text-right px-5 py-4 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        @php
                            $roleClass = [
                                'admin' => 'bg-slate-950 text-white border-slate-950',
                                'manager' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'sales_executive' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'support_agent' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            ][$user->role] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                            $statusClass = $user->status === 'active'
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                : 'bg-red-50 text-red-700 border-red-100';
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">

                            {{-- USER --}}
                            <td class="px-5 py-4 min-w-[230px]">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-950 truncate">
                                            {{ $user->name }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            ID: #{{ $user->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- EMAIL --}}
                            <td class="px-5 py-4 min-w-[230px]">
                                <p class="text-slate-700 truncate">
                                    {{ $user->email }}
                                </p>
                            </td>

                            {{-- ROLE --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>

                            {{-- CREATED --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-semibold text-slate-900">
                                    {{ $user->created_at->format('d M Y') }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $user->created_at->format('h:i A') }}
                                </p>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('users.show', $user) }}"
                                       title="View User"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('users.edit', $user) }}"
                                       title="Edit User"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')

                                            <button 
                                                type="submit"
                                                title="Delete User"
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
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <i class="fa-solid fa-users"></i>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                                        No users found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Try changing filters or add a new CRM user.
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
            @forelse($users as $user)
                @php
                    $roleClass = [
                        'admin' => 'bg-slate-950 text-white border-slate-950',
                        'manager' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                        'sales_executive' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'support_agent' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                    ][$user->role] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                    $statusClass = $user->status === 'active'
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                        : 'bg-red-50 text-red-700 border-red-100';
                @endphp

                <div class="px-5 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="font-semibold text-slate-950">
                                    {{ $user->name }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1 break-all">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('users.show', $user) }}"
                               title="View User"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </a>

                            <a href="{{ route('users.edit', $user) }}"
                               title="Edit User"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                <i class="fa-regular fa-pen-to-square text-sm"></i>
                            </a>

                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')

                                    <button 
                                        type="submit"
                                        title="Delete User"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-700 transition hover:bg-red-100"
                                    >
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">User ID</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                #{{ $user->id }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-white/70 px-3 py-3">
                            <p class="text-xs text-slate-400">Created</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $user->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto max-w-sm">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                            <i class="fa-solid fa-users"></i>
                        </div>

                        <h3 class="mt-4 text-base font-semibold text-slate-900">
                            No users found
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Try changing filters or add a new CRM user.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($users->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>

@endsection