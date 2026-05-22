@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Sales Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Edit Deal
            </h1>

            <p class="text-slate-500 mt-2">
                Update deal details, sales stage, expected value and closing information.
            </p>
        </div>

        <a href="{{ route('deals.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
            Back to Deals
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
    <form method="POST" action="{{ route('deals.update', $deal) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- CUSTOMER & ASSIGNMENT -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Customer & Assignment
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update the linked customer and responsible team member.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Customer <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="customer_id"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">Select Customer</option>

                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ old('customer_id', $deal->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} {{ $customer->company_name ? '- '.$customer->company_name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Assign To
                    </label>

                    @if(auth()->user()->role === 'sales_executive')

                        <!-- Sales executive ke liye sirf naam show hoga, change nahi kar sakta -->
                        <div class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 cursor-not-allowed">
                            {{ $deal->assignedUser->name ?? 'Unassigned' }}
                            @if($deal->assignedUser)
                                <span class="text-slate-400 font-normal">
                                    - {{ ucwords(str_replace('_', ' ', $deal->assignedUser->role)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Hidden input old assigned_to value ko preserve karega -->
                        <input type="hidden" name="assigned_to" value="{{ $deal->assigned_to }}">

                    @else

                        <!-- Admin / Manager ke liye editable select -->
                        <select 
                            name="assigned_to"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="">Unassigned</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to', $deal->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                </option>
                            @endforeach
                        </select>

                    @endif
                </div>
            </div>
        </div>

        <!-- DEAL DETAILS -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Deal Details
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update deal title, amount, stage and closing probability.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Deal Title <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="text" 
                        name="title" 
                        value="{{ old('title', $deal->title) }}"
                        placeholder="Example: Ecommerce Website Development"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Amount <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="number" 
                        name="amount" 
                        value="{{ old('amount', $deal->amount) }}"
                        placeholder="45000"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Probability (%) <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="number" 
                        name="probability" 
                        value="{{ old('probability', $deal->probability) }}"
                        min="0" 
                        max="100"
                        placeholder="0 - 100"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Stage <span class="text-red-500">*</span>
                    </label>

                    <select 
                        name="stage"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['new','qualified','proposal_sent','negotiation','won','lost'] as $stage)
                            <option value="{{ $stage }}"
                                {{ old('stage', $deal->stage) == $stage ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $stage)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Expected Close Date
                    </label>

                    <input 
                        type="date" 
                        name="expected_close_date"
                        value="{{ old('expected_close_date', $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>
            </div>
        </div>

        <!-- CLOSING & NOTES -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Closing & Notes
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Add lost reason if applicable and update important deal notes.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Lost Reason
                    </label>

                    <textarea 
                        name="lost_reason" 
                        rows="3"
                        placeholder="Fill this only if the deal is lost"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >{{ old('lost_reason', $deal->lost_reason) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Description
                    </label>

                    <textarea 
                        name="description" 
                        rows="5"
                        placeholder="Deal requirements, proposal details, negotiation notes..."
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >{{ old('description', $deal->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
            <a href="{{ route('deals.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                Cancel
            </a>

            <button 
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
            >
                Update Deal
            </button>
        </div>

    </form>

</div>

@endsection