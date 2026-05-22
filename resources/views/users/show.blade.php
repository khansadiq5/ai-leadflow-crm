@extends('layouts.app')

@section('content')

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

    $roleIcon = [
        'admin' => 'fa-user-shield',
        'manager' => 'fa-user-tie',
        'sales_executive' => 'fa-chart-line',
        'support_agent' => 'fa-headset',
    ][$user->role] ?? 'fa-user';
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Team Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950 truncate">
                {{ $user->name }}
            </h1>

            <p class="text-slate-500 mt-2">
                User profile, role and account status.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 shrink-0">
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Back
            </a>

            <a href="{{ route('users.edit', $user) }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                Edit User
            </a>
        </div>
    </div>

    {{-- USER OVERVIEW STRIP --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4 min-w-0">
                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-lg font-semibold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <h2 class="text-lg font-semibold text-slate-950 truncate">
                        {{ $user->name }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1 break-all">
                        {{ $user->email }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                            <i class="fa-solid {{ $roleIcon }} mr-1.5 text-[10px]"></i>
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[560px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        User ID
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        #{{ $user->id }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Created
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Last Updated
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $user->updated_at->format('d M Y') }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT MAIN INFO --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- USER INFORMATION --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        User Information
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Basic account details, role and CRM access status.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Name
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $user->name }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Email
                        </p>
                        <p class="font-semibold text-slate-900 mt-2 break-all">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Role
                        </p>
                        <span class="inline-flex items-center mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                            <i class="fa-solid {{ $roleIcon }} mr-1.5 text-[10px]"></i>
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Status
                        </p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created At
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $user->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Last Updated
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $user->updated_at->format('d M Y, h:i A') }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- ACCESS DETAILS --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Access Details
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Role-based permissions summary for this user.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $roleIcon }} text-sm"></i>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-950">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }} Access
                            </p>

                            <p class="text-sm text-slate-500 mt-2 leading-7">
                                @if($user->role === 'admin')
                                    Full CRM access including users, reports, tickets, sales modules and delete permissions.
                                @elseif($user->role === 'manager')
                                    Can manage sales workflow, tickets and team performance, except user administration.
                                @elseif($user->role === 'sales_executive')
                                    Can manage assigned leads, customers, deals and tasks.
                                @elseif($user->role === 'support_agent')
                                    Can manage assigned support tickets and support customers.
                                @else
                                    Role permissions are not defined for this account.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="space-y-6">

            {{-- PROFILE CARD --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm text-center">
                <div class="mx-auto h-20 w-20 rounded-3xl bg-slate-950 text-white flex items-center justify-center text-2xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h2 class="text-xl font-semibold text-slate-950 mt-4 truncate">
                    {{ $user->name }}
                </h2>

                <p class="text-sm text-slate-500 mt-1 break-all">
                    {{ $user->email }}
                </p>

                <div class="mt-4 flex flex-wrap justify-center gap-2">
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                        <i class="fa-solid {{ $roleIcon }} mr-1.5 text-[10px]"></i>
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </span>

                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>

            {{-- ACCOUNT SUMMARY --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Account Summary
                </h2>

                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-slate-500">User ID</span>
                        <span class="font-semibold text-slate-900">#{{ $user->id }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-slate-500">Status</span>
                        <span class="font-semibold {{ $user->status === 'active' ? 'text-emerald-700' : 'text-red-700' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-slate-500">Created</span>
                        <span class="font-semibold text-slate-900">
                            {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- QUICK ACTION --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Quick Action
                </h2>

                <p class="mt-3 text-sm text-slate-500 leading-6">
                    Update user role, status, email or password from the edit page.
                </p>

                <a href="{{ route('users.edit', $user) }}"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                    <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                    Edit User
                </a>
            </div>

        </div>

    </div>

</div>

@endsection