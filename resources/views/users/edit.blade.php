@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Team Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Edit User
            </h1>

            <p class="text-slate-500 mt-2">
                Update user information, role, account status and login credentials.
            </p>
        </div>

        <a href="{{ route('users.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
            Back
        </a>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm text-red-700">
            <p class="font-semibold mb-2">Please fix the following errors:</p>

            <div class="space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- FORM CARD --}}
    <form method="POST" action="{{ route('users.update', $user) }}"
          class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        @csrf
        @method('PUT')

        {{-- CARD HEADER --}}
        <div class="border-b border-slate-100 px-5 sm:px-6 py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        User Information
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Modify profile details, CRM role and optional password.
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                    ID: #{{ $user->id }}
                </span>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div class="px-5 sm:px-6 py-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- NAME --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Enter full name"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        placeholder="Enter email address"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                {{-- ROLE --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="role"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['admin','manager','sales_executive','support_agent'] as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['active','inactive'] as $status)
                            <option value="{{ $status }}" {{ old('status', $user->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        New Password
                    </label>

                    <input 
                        type="password"
                        name="password"
                        placeholder="Leave blank to keep current password"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >

                    <p class="mt-2 text-xs text-slate-400">
                        Fill only when you want to change this user's password.
                    </p>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Confirm New Password
                    </label>

                    <input 
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm new password"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >

                    <p class="mt-2 text-xs text-slate-400">
                        Must match the new password field.
                    </p>
                </div>

            </div>

            {{-- USER SUMMARY --}}
            <div class="mt-6 rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-11 w-11 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <p class="font-semibold text-slate-950 truncate">
                                {{ $user->name }}
                            </p>

                            <p class="text-sm text-slate-500 truncate">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>

                        <span class="inline-flex rounded-full border {{ $user->status === 'active' ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-red-100 bg-red-50 text-red-700' }} px-3 py-1 text-xs font-semibold">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- FORM FOOTER --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-slate-100 px-5 sm:px-6 py-5">
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                Cancel
            </a>

            <button 
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
            >
                <i class="fa-solid fa-floppy-disk mr-2 text-xs"></i>
                Update User
            </button>
        </div>

    </form>

</div>

@endsection