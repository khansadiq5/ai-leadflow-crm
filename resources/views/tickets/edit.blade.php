@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Support Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Edit Support Ticket
            </h1>

            <p class="text-slate-500 mt-2">
                Update ticket details, assignment, priority and resolution status.
            </p>
        </div>

        <a href="{{ route('tickets.index') }}"
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
    <form method="POST" action="{{ route('tickets.update', $ticket) }}"
          class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">
        @csrf
        @method('PUT')

        {{-- CARD HEADER --}}
        <div class="border-b border-slate-100 px-5 sm:px-6 py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Ticket Information
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Modify support ticket details and customer issue status.
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                    #{{ $ticket->id }}
                </span>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div class="px-5 sm:px-6 py-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- CUSTOMER --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Customer <span class="text-red-500">*</span>
                    </label>

                    @if(auth()->user()->role === 'support_agent')
                        <input type="hidden" name="customer_id" value="{{ $ticket->customer_id }}">

                        <div class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-600">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-user text-xs"></i>
                                </div>

                                <p class="font-semibold truncate">
                                    {{ $ticket->customer->name ?? 'Customer' }}
                                    {{ $ticket->customer && $ticket->customer->company_name ? '- '.$ticket->customer->company_name : '' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <select 
                            name="customer_id"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="">Select Customer</option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', $ticket->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} {{ $customer->company_name ? '- '.$customer->company_name : '' }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- ASSIGN TO --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Assign To
                    </label>

                    @if(auth()->user()->role === 'support_agent')
                        <input type="hidden" name="assigned_to" value="{{ $ticket->assigned_to }}">

                        <div class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-600">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-headset text-xs"></i>
                                </div>

                                <p class="font-semibold truncate">
                                    {{ $ticket->assignedUser->name ?? 'Unassigned' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <select 
                            name="assigned_to"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="">Unassigned</option>

                            @foreach($supportAgents as $agent)
                                <option value="{{ $agent->id }}"
                                    {{ old('assigned_to', $ticket->assigned_to) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }} - Support Agent
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- SUBJECT --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Subject <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="text"
                        name="subject"
                        value="{{ old('subject', $ticket->subject) }}"
                        placeholder="Example: Payment gateway not working"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="category"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['technical','billing','general','feature_request','complaint'] as $category)
                            <option value="{{ $category }}"
                                {{ old('category', $ticket->category) == $category ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PRIORITY --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="priority"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['low','medium','high','urgent'] as $priority)
                            <option value="{{ $priority }}"
                                {{ old('priority', $ticket->priority) == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
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
                        @foreach(['open','in_progress','resolved','closed'] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DESCRIPTION --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>

                    <textarea 
                        name="description"
                        rows="6"
                        placeholder="Explain the customer issue, complaint or support request..."
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >{{ old('description', $ticket->description) }}</textarea>
                </div>

            </div>

        </div>

        {{-- FORM FOOTER --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-slate-100 px-5 sm:px-6 py-5">
            <a href="{{ route('tickets.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                Cancel
            </a>

            <button 
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
            >
                <i class="fa-solid fa-floppy-disk mr-2 text-xs"></i>
                Update Ticket
            </button>
        </div>

    </form>

</div>

@endsection