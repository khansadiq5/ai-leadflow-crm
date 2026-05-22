@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    @php
        $priorityClass = match($lead->priority) {
            'hot' => 'bg-red-50 text-red-700 border-red-100',
            'warm' => 'bg-amber-50 text-amber-700 border-amber-100',
            'cold' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200'
        };

        $statusClass = match($lead->status) {
            'new' => 'bg-blue-50 text-blue-700 border-blue-100',
            'contacted' => 'bg-cyan-50 text-cyan-700 border-cyan-100',
            'interested' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'demo_scheduled' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
            'proposal_sent' => 'bg-purple-50 text-purple-700 border-purple-100',
            'negotiation' => 'bg-amber-50 text-amber-700 border-amber-100',
            'converted' => 'bg-green-50 text-green-700 border-green-100',
            'lost' => 'bg-red-50 text-red-700 border-red-100',
            default => 'bg-slate-100 text-slate-700 border-slate-200'
        };
    @endphp

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Lead Profile
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                {{ $lead->name }}
            </h1>

            <p class="text-slate-500 mt-2">
                {{ $lead->company_name ?? 'No company added' }}
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('leads.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Back
            </a>

            <a href="{{ route('leads.edit', $lead) }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                Edit Lead
            </a>

            @if($lead->status !== 'converted')
                <form method="POST" action="{{ route('leads.convert', $lead) }}"
                      onsubmit="return confirm('Are you sure you want to convert this lead to customer?')">
                    @csrf

                    <button 
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl border border-emerald-200 bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 hover:border-emerald-700 active:scale-[0.99]">
                        <i class="fa-solid fa-user-check mr-2 text-xs"></i>
                        Convert to Customer
                    </button>
                </form>
            @else
                <a href="{{ route('customers.show', $lead->converted_customer_id) }}"
                   class="inline-flex items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100 hover:border-emerald-300 active:scale-[0.99]">
                    <i class="fa-regular fa-address-card mr-2 text-xs"></i>
                    View Customer
                </a>
            @endif
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

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

    <!-- LEAD OVERVIEW STRIP -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-lg font-semibold shrink-0">
                    {{ strtoupper(substr($lead->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        {{ $lead->name }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $lead->company_name ?? 'No company added' }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($lead->priority) }} Priority
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[520px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Phone
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $lead->phone }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Assigned To
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $lead->assignedUser->name ?? 'Unassigned' }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Follow-up
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $lead->follow_up_date ?? 'Not scheduled' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT MAIN INFO -->
        <div class="lg:col-span-2 space-y-6">

            <!-- LEAD INFORMATION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Lead Information
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Complete contact and inquiry details for this lead.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Name</p>
                        <p class="font-semibold text-slate-900 mt-2">{{ $lead->name }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phone</p>
                        <p class="font-semibold text-slate-900 mt-2">{{ $lead->phone }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</p>
                        <p class="font-semibold text-slate-900 mt-2 break-all">{{ $lead->email ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Company</p>
                        <p class="font-semibold text-slate-900 mt-2">{{ $lead->company_name ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Source</p>
                        <p class="font-semibold text-slate-900 mt-2">{{ $lead->source ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Interested Service</p>
                        <p class="font-semibold text-slate-900 mt-2">{{ $lead->interested_service ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Budget</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $lead->budget ? '₹' . number_format($lead->budget, 2) : '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Follow-up Date</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $lead->follow_up_date ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Created At</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $lead->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Last Updated</p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $lead->updated_at->format('d M Y') }}
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
                    {{ $lead->description ?? 'No description added.' }}
                </p>
            </div>

            <!-- LEAD NOTES -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Lead Notes
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Add lead discussion notes, follow-up updates and internal CRM remarks.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $lead->notes->count() }} Notes
                    </span>
                </div>

                <!-- ADD NOTE FORM -->
                <form method="POST" action="{{ route('notes.store') }}" class="mb-6">
                    @csrf

                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Add New Note
                        </label>

                        <textarea 
                            name="note" 
                            rows="4"
                            placeholder="Add a note about this lead..."
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
                    @forelse($lead->notes->sortByDesc('created_at') as $note)

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
                                Add your first lead note from the form above.
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>
            <!--AI -->
                <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                    {{-- HEADER --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">

                        <div class="flex items-start gap-3 min-w-0">
                            <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                            </div>

                            <div class="min-w-0">
                                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                                    AI Follow-up Assistant
                                </h2>

                                <p class="text-sm text-slate-500 mt-1 leading-6">
                                    Generate a professional follow-up message using lead details.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('ai.leads.followup', $lead) }}" class="shrink-0">
                            @csrf

                            <button 
                                type="submit"
                                class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                            >
                                <i class="fa-solid fa-sparkles mr-2 text-xs"></i>
                                Generate Message
                            </button>
                        </form>

                    </div>

                    {{-- BODY --}}
                    <div class="px-5 sm:px-6 py-5">

                        @if(session('ai_followup_message'))
                            @php
                                $cleanPhone = preg_replace('/\D/', '', $lead->phone);
                                $whatsappText = urlencode(session('ai_followup_message'));
                            @endphp

                            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-4 py-4">
                                <div class="flex items-start gap-3">

                                    <div class="h-9 w-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-robot text-xs"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 mb-2">
                                            Generated Follow-up Message
                                        </p>

                                        <p id="aiLeadMessage" class="text-sm text-slate-700 leading-7 whitespace-pre-line">
                                            {{ session('ai_followup_message') }}
                                        </p>

                                        <div class="mt-5 flex flex-col sm:flex-row sm:flex-wrap gap-3">

                                            <button 
                                                type="button"
                                                onclick="copyAiText('aiLeadMessage')"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/90 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300"
                                            >
                                                <i class="fa-regular fa-copy mr-2 text-xs"></i>
                                                Copy Message
                                            </button>

                                            {{-- WhatsApp feature future update ke liye comment rakha hai --}}
                                            {{--
                                            @if($cleanPhone)
                                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $whatsappText }}"
                                                target="_blank"
                                                class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                                                    <i class="fa-brands fa-whatsapp mr-2 text-sm"></i>
                                                    Send on WhatsApp
                                                </a>
                                            @endif
                                            --}}

                                            @if($lead->email)
                                                <form method="POST" action="{{ route('ai.leads.sendFollowupEmail', $lead) }}">
                                                    @csrf

                                                    <input 
                                                        type="hidden" 
                                                        name="message" 
                                                        value="{{ session('ai_followup_message') }}"
                                                    >

                                                    <button 
                                                        type="submit"
                                                        class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                                                    >
                                                        <i class="fa-regular fa-envelope mr-2 text-xs"></i>
                                                        Send Email
                                                    </button>
                                                </form>
                                            @endif

                                        </div>

                                        <p id="copySuccessText" class="hidden mt-3 text-xs font-semibold text-emerald-700">
                                            <i class="fa-solid fa-circle-check mr-1"></i>
                                            Message copied successfully.
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
                                    No follow-up message generated yet
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Click generate message to create a professional lead follow-up.
                                </p>
                            </div>
                        @endif

                    </div>

                </div>

                @if(session('ai_followup_message'))
                    <script>
                        function copyAiText(id) {
                            const element = document.getElementById(id);
                            const successText = document.getElementById('copySuccessText');

                            if (!element) return;

                            const text = element.innerText;

                            navigator.clipboard.writeText(text).then(function () {
                                if (successText) {
                                    successText.classList.remove('hidden');

                                    setTimeout(function () {
                                        successText.classList.add('hidden');
                                    }, 2500);
                                }
                            });
                        }
                    </script>
                @endif

        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">

            <!-- STATUS CARD -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Lead Status
                </h2>

                <div class="mt-5 space-y-5">

                    <div>
                        <p class="text-sm text-slate-500">Current Status</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Priority Level</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($lead->priority) }}
                        </span>
                    </div>

                    <div class="pt-5 border-t border-slate-200">
                        <p class="text-sm text-slate-500">Assigned User</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $lead->assignedUser->name ?? 'Unassigned' }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- NEXT ACTION -->
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Next Action
                </h2>

                <div class="mt-4 rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                    <p class="text-sm font-semibold text-slate-900">
                        Follow-up Reminder
                    </p>

                    <p class="mt-2 text-sm text-slate-500 leading-6">
                        {{ $lead->follow_up_date 
                            ? 'Next follow-up is scheduled on ' . $lead->follow_up_date . '.' 
                            : 'No follow-up date has been added for this lead.' 
                        }}
                    </p>
                </div>

                <a href="{{ route('leads.edit', $lead) }}"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                    Update Lead
                </a>
            </div>

            <!-- CRM NOTE -->
            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-950 px-5 py-6 shadow-sm text-white">
                <h2 class="text-lg font-semibold">
                    CRM Note
                </h2>

                <p class="mt-3 text-sm text-white/60 leading-6">
                    Lead notes, activity timeline, tasks and convert-to-customer option can be added here in the next module.
                </p>
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

                        <a href="{{ route('leads.activities', $lead) }}"
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
                    @forelse($lead->activities->sortByDesc('created_at')->take(5) as $activity)

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