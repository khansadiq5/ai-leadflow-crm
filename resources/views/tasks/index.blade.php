@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Follow-up Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Tasks & Follow-ups
            </h1>

            <p class="text-slate-500 mt-2">
                Manage calls, reminders, follow-ups and daily CRM activities.
            </p>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-solid fa-plus mr-2 text-xs"></i>
                Add Task
            </a>
        @endif
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
                Filter Tasks
            </h2>

            <p class="text-sm text-slate-500">
                Search and filter tasks by status, priority or assigned user.
            </p>
        </div>

        <form method="GET" action="{{ route('tasks.index') }}" class="px-5 py-5">

            @if(auth()->user()->role === 'sales_executive')
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-end">
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
            @endif

                <!-- Search -->
                <div class="xl:col-span-{{ auth()->user()->role === 'sales_executive' ? '1' : '1' }}">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Search Task
                    </label>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search task, lead, customer..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 pl-11 pr-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Status
                    </label>

                    <select 
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">All Status</option>
                        @foreach(['pending','in_progress','completed','cancelled'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority -->
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

                <!-- Assigned User only Admin/Manager -->
                @if(auth()->user()->role !== 'sales_executive')
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
                @endif

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-filter mr-2 text-xs"></i>
                        Filter
                    </button>

                    @if(request('search') || request('status') || request('priority') || request('assigned_to'))
                        <a href="{{ route('tasks.index') }}"
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
                Task Records
            </h2>

            <p class="text-sm text-slate-500">
                View and manage CRM follow-up tasks.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-slate-500">
                    <tr>
                        <th class="text-left px-5 py-4 font-semibold">Task</th>
                        <th class="text-left px-5 py-4 font-semibold">Related To</th>
                        <th class="text-left px-5 py-4 font-semibold">Due Date</th>
                        <th class="text-left px-5 py-4 font-semibold">Priority</th>
                        <th class="text-left px-5 py-4 font-semibold">Status</th>
                        <th class="text-left px-5 py-4 font-semibold">Assigned</th>
                        <th class="text-right px-5 py-4 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($tasks as $task)

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

                        <tr class="transition {{ $isOverdue ? 'bg-red-50/40 hover:bg-red-50/60' : 'hover:bg-slate-50/70' }}">
                            
                            <!-- Task -->
                            <td class="px-5 py-4 min-w-[260px]">
                                <div class="flex items-start gap-3">
                                    <div class="h-10 w-10 rounded-xl {{ $isOverdue ? 'bg-red-600' : 'bg-slate-950' }} text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        <i class="fa-solid {{ $isOverdue ? 'fa-triangle-exclamation' : 'fa-list-check' }} text-xs"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-950 truncate">
                                            {{ $task->title }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1 truncate">
                                            Created by {{ $task->createdBy->name ?? '-' }}
                                        </p>

                                        @if($isOverdue)
                                            <span class="inline-flex mt-2 rounded-full border border-red-100 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                <i class="fa-solid fa-clock mr-1"></i>
                                                Overdue
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Related -->
                            <td class="px-5 py-4 min-w-[180px]">
                                @if($task->lead)
                                    <p class="font-semibold text-slate-900">Lead</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $task->lead->name }}</p>
                                @elseif($task->customer)
                                    <p class="font-semibold text-slate-900">Customer</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $task->customer->name }}</p>
                                @elseif($task->deal)
                                    <p class="font-semibold text-slate-900">Deal</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $task->deal->title }}</p>
                                @else
                                    <p class="text-slate-400">General Task</p>
                                @endif
                            </td>

                            <!-- Due -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-semibold text-slate-900">
                                    {{ $task->due_date->format('d M Y') }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $task->due_date->format('h:i A') }}
                                </p>
                            </td>

                            <!-- Priority -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>

                            <!-- Assigned -->
                            <td class="px-5 py-4 min-w-[150px] text-slate-700">
                                {{ $task->assignedUser->name ?? 'Unassigned' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <!-- Sales Executive / Admin can mark complete -->
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
                                        <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button 
                                                type="submit"
                                                title="Mark Complete"
                                                onclick="return confirm('Mark this task as completed?')"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-emerald-100 bg-emerald-50 text-emerald-700 transition hover:bg-emerald-100">
                                                <i class="fa-solid fa-check text-sm"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Everyone allowed user can view task -->
                                    <a href="{{ route('tasks.show', $task) }}"
                                    title="View Task"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <!-- Edit only Admin and Manager -->
                                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                                        <a href="{{ route('tasks.edit', $task) }}"
                                        title="Edit Task"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white transition hover:bg-slate-700">
                                            <i class="fa-regular fa-pen-to-square text-sm"></i>
                                        </a>
                                    @endif

                                    <!-- Delete only Admin -->
                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this task?')">
                                            @csrf
                                            @method('DELETE')

                                            <button 
                                                type="submit"
                                                title="Delete Task"
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
                            <td colspan="7" class="px-5 py-14 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <i class="fa-solid fa-list-check"></i>
                                    </div>

                                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                                        No tasks found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Try changing filters or add a new follow-up task.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-5 py-4">
            {{ $tasks->links() }}
        </div>
    </div>

</div>

@endsection