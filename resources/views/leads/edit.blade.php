@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Lead Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Edit Lead
            </h1>

            <p class="text-slate-500 mt-2">
                Update lead information, status, priority and assignment details.
            </p>
        </div>

        <a href="{{ route('leads.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            Back to Leads
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
    <form method="POST" action="{{ route('leads.update', $lead) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- BASIC INFO -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Basic Information
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update the main contact details for this lead.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Lead Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $lead->name) }}"
                        placeholder="Enter lead name"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Phone <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', $lead->phone) }}"
                        placeholder="Enter phone number"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $lead->email) }}"
                        placeholder="name@example.com"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Company Name
                    </label>
                    <input 
                        type="text" 
                        name="company_name" 
                        value="{{ old('company_name', $lead->company_name) }}"
                        placeholder="Company or organization"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>
            </div>
        </div>

        <!-- SALES DETAILS -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Sales Details
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update source, service interest, budget and current lead stage.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Source
                    </label>
                    <select 
                        name="source"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">Select Source</option>
                        @foreach(['Website','Referral','Instagram','Facebook','LinkedIn','Walk-in','Cold Call'] as $source)
                            <option value="{{ $source }}" {{ old('source', $lead->source) == $source ? 'selected' : '' }}>
                                {{ $source }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Interested Service
                    </label>
                    <input 
                        type="text" 
                        name="interested_service" 
                        value="{{ old('interested_service', $lead->interested_service) }}"
                        placeholder="CRM, Website, App, Marketing..."
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Budget
                    </label>
                    <input 
                        type="number" 
                        name="budget" 
                        value="{{ old('budget', $lead->budget) }}"
                        placeholder="Expected budget"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Follow-up Date
                    </label>
                    <input 
                        type="date" 
                        name="follow_up_date" 
                        value="{{ old('follow_up_date', $lead->follow_up_date) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>
                    <select 
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['new','contacted','interested','demo_scheduled','proposal_sent','negotiation','converted','lost'] as $status)
                            <option value="{{ $status }}" {{ old('status', $lead->status) == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Priority
                    </label>
                    <select 
                        name="priority"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['hot','warm','cold'] as $priority)
                            <option value="{{ $priority }}" {{ old('priority', $lead->priority) == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- ASSIGNMENT & NOTES -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Assignment & Notes
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update assigned team member and add useful notes for follow-ups.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Assign To
                    </label>

                    @if(auth()->user()->role === 'sales_executive')

                        <!-- Sales executive ko assigned user dikhega, but change nahi kar sakta -->
                        <div class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 cursor-not-allowed">
                            {{ $lead->assignedUser->name ?? 'Unassigned' }}

                            @if($lead->assignedUser)
                                <span class="text-slate-400 font-normal">
                                    - {{ ucwords(str_replace('_', ' ', $lead->assignedUser->role)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Old assigned_to value preserve rahegi -->
                        <input type="hidden" name="assigned_to" value="{{ $lead->assigned_to }}">

                    @else

                        <!-- Admin / Manager ke liye editable select -->
                        <select 
                            name="assigned_to"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="">Unassigned</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                </option>
                            @endforeach
                        </select>

                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Description
                    </label>
                    <textarea 
                        name="description" 
                        rows="5"
                        placeholder="Add notes, requirements, conversation summary or next action..."
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >{{ old('description', $lead->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
            <a href="{{ route('leads.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                Cancel
            </a>

            <button 
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
            >
                Update Lead
            </button>
        </div>

    </form>

</div>

@endsection