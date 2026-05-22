@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    @php
        $stageClass = [
            'new' => 'bg-blue-50 text-blue-700 border-blue-100',
            'qualified' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
            'proposal_sent' => 'bg-amber-50 text-amber-700 border-amber-100',
            'negotiation' => 'bg-orange-50 text-orange-700 border-orange-100',
            'won' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'lost' => 'bg-red-50 text-red-700 border-red-100',
        ][$deal->stage] ?? 'bg-slate-100 text-slate-700 border-slate-200';

        $probabilityColor = $deal->probability >= 70
            ? 'bg-emerald-500'
            : ($deal->probability >= 40 ? 'bg-amber-500' : 'bg-slate-400');
    @endphp

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Deal Details
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                {{ $deal->title }}
            </h1>

            <p class="text-slate-500 mt-2">
                {{ $deal->customer->name ?? 'No customer' }}
                @if($deal->customer && $deal->customer->company_name)
                    - {{ $deal->customer->company_name }}
                @endif
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('deals.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Back
            </a>

            <a href="{{ route('deals.edit', $deal) }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                Edit Deal
            </a>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGE -->
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

    <!-- DEAL OVERVIEW STRIP -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-lg font-semibold shrink-0">
                    {{ strtoupper(substr($deal->title, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        {{ $deal->title }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $deal->customer->name ?? 'No customer linked' }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $stageClass }}">
                            {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                        </span>

                        <span class="inline-flex rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $deal->probability }}% Probability
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[560px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Amount
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        ₹{{ number_format($deal->amount) }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Expected Close
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('d M Y') : '-' }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Assigned To
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $deal->assignedUser->name ?? 'Unassigned' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT MAIN INFO -->
        <div class="lg:col-span-2 space-y-6">

            <!-- DEAL INFORMATION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Deal Information
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Complete sales opportunity, customer and closing details.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Deal Title
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->title }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Customer
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->customer->name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Amount
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            ₹{{ number_format($deal->amount) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Probability
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->probability }}%
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Expected Close Date
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('d M Y') : '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Closed At
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->closed_at ? $deal->closed_at->format('d M Y') : '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Assigned To
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->assignedUser->name ?? 'Unassigned' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created By
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->createdBy->name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created Date
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Last Updated
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $deal->updated_at->format('d M Y') }}
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
                    {{ $deal->description ?? 'No description added.' }}
                </p>
            </div>

            <!-- LOST REASON -->
            @if($deal->stage === 'lost')
                <div class="rounded-[1.5rem] border border-red-100 bg-red-50 px-5 sm:px-6 py-6 shadow-sm">
                    <h2 class="text-lg font-semibold tracking-tight text-red-700">
                        Lost Reason
                    </h2>

                    <p class="mt-4 text-sm text-red-600 leading-7">
                        {{ $deal->lost_reason ?? 'No lost reason added.' }}
                    </p>
                </div>
            @endif

            <!-- DEAL NOTES -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Deal Notes
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Add negotiation updates, proposal discussions and internal deal remarks.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $deal->notes->count() }} Notes
                    </span>
                </div>

                <!-- ADD NOTE FORM -->
                <form method="POST" action="{{ route('notes.store') }}" class="mb-6">
                    @csrf

                    <input type="hidden" name="deal_id" value="{{ $deal->id }}">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Add New Note
                        </label>

                        <textarea 
                            name="note" 
                            rows="4"
                            placeholder="Add negotiation, proposal or deal discussion note..."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >{{ old('note') }}</textarea>
                    </div>

                    <div class="flex justify-end mt-3">
                        <button 
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                        >
                            <i class="fa-solid fa-plus mr-2 text-xs"></i>
                            Add Note
                        </button>
                    </div>
                </form>

                <!-- NOTES LIST -->
                <div class="space-y-4">
                    @forelse($deal->notes->sortByDesc('created_at') as $note)

                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                            <div class="flex items-start justify-between gap-4">

                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ $note->user ? strtoupper(substr($note->user->name, 0, 1)) : 'N' }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm text-slate-700 leading-7 whitespace-pre-line">
                                            {{ $note->note }}
                                        </p>

                                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                            <span>
                                                Added by 
                                                <span class="font-semibold text-slate-600">
                                                    {{ $note->user->name ?? '-' }}
                                                </span>
                                            </span>

                                            <span>•</span>

                                            <span>
                                                {{ $note->created_at->format('d M Y, h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->role === 'admin' || $note->user_id === auth()->id())
                                    <form method="POST" action="{{ route('notes.destroy', $note) }}"
                                          onsubmit="return confirm('Delete this note?')"
                                          class="shrink-0">
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            type="submit"
                                            title="Delete Note"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-700 transition hover:bg-red-100"
                                        >
                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>

                    @empty

                        <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-10 text-center">
                            <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                <i class="fa-regular fa-note-sticky"></i>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-slate-900">
                                No notes added yet
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Add your first deal discussion note from the form above.
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">

            <!-- STATUS CARD -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Deal Status
                </h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <p class="text-sm text-slate-500">Current Stage</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $stageClass }}">
                            {{ ucwords(str_replace('_', ' ', $deal->stage)) }}
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <p class="text-sm text-slate-500">Probability</p>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $deal->probability }}%
                            </p>
                        </div>

                        <div class="h-3 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div 
                                class="h-full rounded-full {{ $probabilityColor }}" 
                                style="width: {{ $deal->probability }}%">
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 mt-2">
                            {{ $deal->probability }}% chance of closing
                        </p>
                    </div>

                    <div class="pt-5 border-t border-slate-200">
                        <p class="text-sm text-slate-500">Expected Close Date</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('d M Y') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- CUSTOMER CARD -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Customer
                </h2>

                <div class="mt-5 flex items-start gap-3">
                    <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                        {{ $deal->customer ? strtoupper(substr($deal->customer->name, 0, 1)) : '-' }}
                    </div>

                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 truncate">
                            {{ $deal->customer->name ?? '-' }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1 truncate">
                            {{ $deal->customer->phone ?? '-' }}
                        </p>
                    </div>
                </div>

                @if($deal->customer)
                    <a href="{{ route('customers.show', $deal->customer) }}"
                       class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                        <i class="fa-regular fa-address-card mr-2 text-xs"></i>
                        View Customer
                    </a>
                @endif
            </div>

            <!-- QUICK ACTION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Quick Action
                </h2>

                <p class="mt-3 text-sm text-slate-500 leading-6">
                    Update deal value, stage, closing date or notes as the opportunity moves forward.
                </p>

                <a href="{{ route('deals.edit', $deal) }}"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                    <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                    Update Deal
                </a>
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

                        <a href="{{ route('deals.activities', $deal) }}"
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
                    @forelse($deal->activities->sortByDesc('created_at') as $activity)

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
                                Deal activities will appear here after updates or system actions.
                            </p>
                        </div>

                    @endforelse
                </div>

            </div>

        </div>

    </div>

</div>

@endsection