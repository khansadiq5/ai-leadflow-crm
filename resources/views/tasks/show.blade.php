@extends('layouts.app')

@section('content')

@php
    $isOverdue = $task->due_date->isPast() && !in_array($task->status, ['completed', 'cancelled']);

    $priorityClass = [
        'low' => 'bg-slate-100 text-slate-700 border-slate-200',
        'medium' => 'bg-blue-50 text-blue-700 border-blue-100',
        'high' => 'bg-orange-50 text-orange-700 border-orange-100',
        'urgent' => 'bg-red-50 text-red-700 border-red-100',
    ][$task->priority] ?? 'bg-slate-100 text-slate-700 border-slate-200';

    $statusClass = [
        'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'cancelled' => 'bg-slate-100 text-slate-700 border-slate-200',
    ][$task->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Task Details
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                {{ $task->title }}
            </h1>

            <p class="text-slate-500 mt-2">
                Follow-up and activity details for this CRM task.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('tasks.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Back
            </a>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <a href="{{ route('tasks.edit', $task) }}"
                   class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                    <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                    Edit Task
                </a>
            @endif

            @if(
                $task->status !== 'completed' &&
                $task->status !== 'cancelled' &&
                (
                    auth()->user()->role === 'admin' ||
                    (
                        auth()->user()->role === 'sales_executive' &&
                        $task->assigned_to === auth()->id()
                    )
                )
            )
                <form method="POST" action="{{ route('tasks.complete', $task) }}"
                      onsubmit="return confirm('Mark this task as completed?')">
                    @csrf
                    @method('PATCH')

                    <button 
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 active:scale-[0.99]">
                        <i class="fa-solid fa-check mr-2 text-xs"></i>
                        Mark Complete
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- OVERDUE MESSAGE -->
    @if($isOverdue)
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            This task is overdue. Please complete or reschedule it.
        </div>
    @endif

    <!-- TASK OVERVIEW STRIP -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl {{ $isOverdue ? 'bg-red-600' : 'bg-slate-950' }} text-white flex items-center justify-center text-lg font-semibold shrink-0">
                    <i class="fa-solid {{ $isOverdue ? 'fa-triangle-exclamation' : 'fa-list-check' }} text-sm"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        {{ $task->title }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Assigned to {{ $task->assignedUser->name ?? 'Unassigned' }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($task->priority) }} Priority
                        </span>

                        @if($isOverdue)
                            <span class="inline-flex rounded-full border border-red-100 bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                Overdue
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[560px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Due Date
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $task->due_date->format('d M Y') }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Time
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $task->due_date->format('h:i A') }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Created By
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $task->createdBy->name ?? '-' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT MAIN INFO -->
        <div class="lg:col-span-2 space-y-6">

            <!-- TASK INFORMATION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Task Information
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Complete follow-up details and ownership information.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Task Title
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $task->title }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Due Date & Time
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $task->due_date->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Assigned To
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $task->assignedUser->name ?? 'Unassigned' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created By
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $task->createdBy->name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created Date
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $task->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Completed At
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $task->completed_at ? $task->completed_at->format('d M Y, h:i A') : '-' }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Description
                </h2>

                <p class="mt-4 text-sm text-slate-600 leading-7">
                    {{ $task->description ?? 'No description added.' }}
                </p>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">

            <!-- STATUS CARD -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Task Status
                </h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <p class="text-sm text-slate-500">Current Status</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Priority Level</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <div class="pt-5 border-t border-slate-200">
                        <p class="text-sm text-slate-500">Overdue</p>
                        <p class="mt-2 font-semibold {{ $isOverdue ? 'text-red-600' : 'text-emerald-700' }}">
                            {{ $isOverdue ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- SALES EXECUTIVE STATUS UPDATE -->
            @if(
                auth()->user()->role === 'sales_executive' &&
                $task->assigned_to === auth()->id() &&
                $task->status !== 'completed' &&
                $task->status !== 'cancelled'
            )
                <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Update Status
                    </h2>

                    <p class="mt-2 text-sm text-slate-500 leading-6">
                        Update the progress of your assigned task.
                    </p>

                    <form method="POST" action="{{ route('tasks.status.update', $task) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <select 
                            name="status"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            @foreach(['pending','in_progress','completed','cancelled'] as $status)
                                <option value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>

                        <button 
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                            Update Status
                        </button>
                    </form>
                </div>
            @endif

            <!-- RELATED RECORD -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Related Record
                </h2>

                <div class="mt-5">
                    @if($task->lead)
                        <div class="flex items-start gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                L
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm text-slate-500">Lead</p>
                                <p class="font-semibold text-slate-900 mt-1 truncate">
                                    {{ $task->lead->name }}
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $task->lead->phone }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('leads.show', $task->lead) }}"
                           class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                            View Lead
                        </a>

                    @elseif($task->customer)
                        <div class="flex items-start gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                C
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm text-slate-500">Customer</p>
                                <p class="font-semibold text-slate-900 mt-1 truncate">
                                    {{ $task->customer->name }}
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $task->customer->phone }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('customers.show', $task->customer) }}"
                           class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                            View Customer
                        </a>

                    @elseif($task->deal)
                        <div class="flex items-start gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                D
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm text-slate-500">Deal</p>
                                <p class="font-semibold text-slate-900 mt-1 truncate">
                                    {{ $task->deal->title }}
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    ₹{{ number_format($task->deal->amount) }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('deals.show', $task->deal) }}"
                           class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                            View Deal
                        </a>

                    @else
                        <p class="text-sm text-slate-500 leading-6">
                            This is a general task and is not linked to any lead, customer or deal.
                        </p>
                    @endif
                </div>
            </div>

            <!-- ACTIVITY TIMELINE -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <div class="mb-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                                Activity Timeline
                            </h2>

                            <span class="inline-block text-sm text-slate-500 mt-1">
                                Latest 5
                            </span>
                        </div>

                        <a href="{{ route('tasks.activities', $task) }}"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950 shrink-0">
                            View All
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    {{-- 
                    <p class="text-sm text-slate-500 mt-1">
                        Track customer updates, notes and system activities.
                    </p> 
                    --}}
                </div>

                <div class="space-y-5 max-h-96 overflow-y-auto pr-2">
                    @forelse($task->activities->sortByDesc('created_at')->take(5) as $activity)

                        <div class="relative flex gap-4">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clock text-xs"></i>
                                </div>

                                @if(!$loop->last)
                                    <div class="mt-3 h-full min-h-8 w-px bg-slate-200"></div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0 rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 leading-6">
                                            {{ $activity->description }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $activity->user->name ?? 'System' }}
                                            • {{ $activity->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                        </p>
                                    </div>

                                    <span class="inline-flex w-fit rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 shrink-0">
                                        {{ ucwords(str_replace('_', ' ', $activity->type)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    @empty

                        <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                            <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                <i class="fa-regular fa-clock"></i>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-slate-900">
                                No activities recorded yet
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Lead activities will appear here after updates or system actions.
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

@endsection