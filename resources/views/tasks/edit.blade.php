@extends('layouts.app')

@section('content')

@if(auth()->user()->role === 'sales_executive')
    <div class="max-w-3xl mx-auto">
        <div class="rounded-[1.5rem] border border-red-100 bg-red-50 px-6 py-6 text-red-700">
            <h2 class="text-lg font-semibold">
                Access Denied
            </h2>

            <p class="mt-2 text-sm leading-6">
                Sales Executive cannot edit tasks. You can view your assigned tasks and update their status only.
            </p>

            <a href="{{ route('tasks.index') }}"
               class="mt-5 inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                Back to Tasks
            </a>
        </div>
    </div>
@else

<div class="max-w-6xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Follow-up Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Edit Task
            </h1>

            <p class="text-slate-500 mt-2">
                Update follow-up details, due date, priority and CRM activity status.
            </p>
        </div>

        <a href="{{ route('tasks.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
            Back to Tasks
        </a>
    </div>

    <!-- ERROR BOX -->
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

    <!-- FORM -->
    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TASK DETAILS -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Task Details
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update task title, deadline, priority and current progress status.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Task Title <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="text" 
                        name="title" 
                        value="{{ old('title', $task->title) }}"
                        placeholder="Example: Call customer for proposal follow-up"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Assign To <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="assigned_to"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">Select User</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Due Date & Time <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="datetime-local" 
                        name="due_date"
                        value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="priority"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['low','medium','high','urgent'] as $priority)
                            <option value="{{ $priority }}" {{ old('priority', $task->priority) == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['pending','in_progress','completed','cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('status', $task->status) == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- RELATED RECORDS -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Related Records
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Connect this task with a lead, customer or deal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Related Lead
                    </label>

                    <select 
                        name="lead_id"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">No Lead</option>

                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}" {{ old('lead_id', $task->lead_id) == $lead->id ? 'selected' : '' }}>
                                {{ $lead->name }} - {{ $lead->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Related Customer
                    </label>

                    <select 
                        name="customer_id"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">No Customer</option>

                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $task->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Related Deal
                    </label>

                    <select 
                        name="deal_id"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">No Deal</option>

                        @foreach($deals as $deal)
                            <option value="{{ $deal->id }}" {{ old('deal_id', $task->deal_id) == $deal->id ? 'selected' : '' }}>
                                {{ $deal->title }} - ₹{{ number_format($deal->amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Description
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update call notes, follow-up details or internal task instructions.
                </p>
            </div>

            <textarea 
                name="description" 
                rows="5"
                placeholder="Task details, call notes, reminder message..."
                class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            >{{ old('description', $task->description) }}</textarea>
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
            <a href="{{ route('tasks.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                Cancel
            </a>

            <button 
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
            >
                Update Task
            </button>
        </div>

    </form>

</div>

@endif

@endsection