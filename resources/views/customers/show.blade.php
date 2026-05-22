@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    @php
        $statusClass = $customer->status === 'active'
            ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
            : 'bg-slate-100 text-slate-700 border-slate-200';

        $typeClass = [
            'individual' => 'bg-blue-50 text-blue-700 border-blue-100',
            'company' => 'bg-purple-50 text-purple-700 border-purple-100',
            'vip' => 'bg-amber-50 text-amber-700 border-amber-100',
            'regular' => 'bg-slate-100 text-slate-700 border-slate-200',
        ][$customer->type] ?? 'bg-slate-100 text-slate-700 border-slate-200';
    @endphp

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Customer Profile
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                {{ $customer->name }}
            </h1>

            <p class="text-slate-500 mt-2">
                {{ $customer->company_name ?? 'No company added' }}
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('customers.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Back
            </a>

            <a href="{{ route('customers.edit', $customer) }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                Edit Customer
            </a>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            <i class="fa-solid fa-circle-check mr-2"></i>
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

    <!-- CUSTOMER OVERVIEW STRIP -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-lg font-semibold shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        {{ $customer->name }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $customer->company_name ?? 'No company added' }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($customer->status) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $typeClass }}">
                            {{ ucfirst($customer->type) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[560px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Phone
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $customer->phone }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Assigned To
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $customer->assignedUser->name ?? 'Unassigned' }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Location
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $customer->city ?? 'No city' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT MAIN INFO -->
        <div class="lg:col-span-2 space-y-6">

            <!-- CUSTOMER INFORMATION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Customer Information
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Complete customer contact, company and ownership details.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Name</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->name }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phone</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->phone }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</p>
                        <p class="font-semibold text-slate-900 mt-2 break-all">
                            {{ $customer->email ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Company</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->company_name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">City</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->city ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">State</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->state ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pincode</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->pincode ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Assigned To</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->assignedUser->name ?? 'Unassigned' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Created By</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->createdBy->name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Created At</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $customer->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- ADDRESS -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Address
                </h2>

                <p class="mt-4 text-sm text-slate-600 leading-7">
                    {{ $customer->address ?? 'No address added.' }}
                </p>
            </div>

            <!-- CUSTOMER NOTES -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Customer Notes
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Add customer discussion notes, follow-up updates and internal CRM remarks.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $customer->customerNotes->count() }} Notes
                    </span>
                </div>

                <!-- ADD NOTE FORM -->
                <form method="POST" action="{{ route('notes.store') }}" class="mb-6">
                    @csrf

                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Add New Note
                        </label>

                        <textarea 
                            name="note" 
                            rows="4"
                            placeholder="Add a note about this customer..."
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
                    @forelse($customer->customerNotes->sortByDesc('created_at') as $note)

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
                                Add your first customer note from the form above.
                            </p>
                        </div>

                    @endforelse
                </div>

            </div>
            
            <!-- AI -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                {{-- HEADER --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">

                    <div class="flex items-start gap-3 min-w-0">
                        <div class="h-11 w-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                                AI Customer Summary
                            </h2>

                            <p class="text-sm text-slate-500 mt-1 leading-6">
                                Generate a quick customer overview with next recommended action.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ai.customers.summary', $customer) }}" class="shrink-0">
                        @csrf

                        <button 
                            type="submit"
                            class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                        >
                            <i class="fa-solid fa-sparkles mr-2 text-xs"></i>
                            Generate Summary
                        </button>
                    </form>

                </div>

                {{-- BODY --}}
                <div class="px-5 sm:px-6 py-5">

                    @if(session('ai_customer_summary'))
                        <div class="rounded-2xl border border-purple-100 bg-purple-50/70 px-4 py-4">
                            <div class="flex items-start gap-3">

                                <div class="h-9 w-9 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-robot text-xs"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-purple-700 mb-2">
                                        Generated Insight
                                    </p>

                                    <p class="text-sm text-slate-700 leading-7 whitespace-pre-line">
                                        {{ session('ai_customer_summary') }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-8 text-center">
                            <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                <i class="fa-solid fa-brain text-sm"></i>
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-slate-900">
                                No AI summary generated yet
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Click generate summary to create a quick customer insight.
                            </p>
                        </div>
                    @endif

                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">

            <!-- STATUS CARD -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Customer Status
                </h2>

                <div class="mt-5 space-y-5">

                    <div>
                        <p class="text-sm text-slate-500">Account Status</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Customer Type</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $typeClass }}">
                            {{ ucfirst($customer->type) }}
                        </span>
                    </div>

                    <div class="pt-5 border-t border-slate-200">
                        <p class="text-sm text-slate-500">Assigned User</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $customer->assignedUser->name ?? 'Unassigned' }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- QUICK ACTION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Quick Action
                </h2>

                <p class="mt-3 text-sm text-slate-500 leading-6">
                    Update customer profile, assignment, status or notes whenever customer details change.
                </p>

                <a href="{{ route('customers.edit', $customer) }}"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                    <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                    Update Customer
                </a>
            </div>

            <!-- CRM NOTE -->
            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-950 px-5 py-6 shadow-sm text-white">
                <h2 class="text-lg font-semibold">
                    CRM Note
                </h2>

                <p class="mt-3 text-sm text-white/60 leading-6">
                    Customer deals, tickets, documents, notes and activity timeline can be added in the next modules.
                </p>
            </div>

            <!-- ACTIVITY TIMELINE -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
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

                        <a href="{{ route('customers.activities', $customer) }}"
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
                    @forelse($customer->activities->sortByDesc('created_at')->take(5) as $activity)

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
                                <p class="text-sm font-semibold text-slate-900 leading-6">
                                    {{ $activity->description }}
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $activity->user->name ?? 'System' }}
                                    • {{ $activity->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                </p>

                                <span class="inline-flex mt-3 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ ucwords(str_replace('_', ' ', $activity->type)) }}
                                </span>
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
                                Customer activities will appear here after updates or system actions.
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

@endsection