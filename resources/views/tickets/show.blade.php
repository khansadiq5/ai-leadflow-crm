@extends('layouts.app')

@section('content')

@php
    $priorityClass = [
        'low' => 'bg-slate-100 text-slate-700 border-slate-200',
        'medium' => 'bg-blue-50 text-blue-700 border-blue-100',
        'high' => 'bg-orange-50 text-orange-700 border-orange-100',
        'urgent' => 'bg-red-50 text-red-700 border-red-100',
    ][$ticket->priority] ?? 'bg-slate-100 text-slate-700 border-slate-200';

    $statusClass = [
        'open' => 'bg-amber-50 text-amber-700 border-amber-100',
        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'closed' => 'bg-slate-100 text-slate-700 border-slate-200',
    ][$ticket->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';

    $categoryClass = [
        'technical' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'billing' => 'bg-purple-50 text-purple-700 border-purple-100',
        'general' => 'bg-slate-100 text-slate-700 border-slate-200',
        'feature_request' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'complaint' => 'bg-red-50 text-red-700 border-red-100',
    ][$ticket->category] ?? 'bg-slate-100 text-slate-700 border-slate-200';

    $ticketIcon = [
        'technical' => 'fa-screwdriver-wrench',
        'billing' => 'fa-credit-card',
        'general' => 'fa-circle-info',
        'feature_request' => 'fa-lightbulb',
        'complaint' => 'fa-triangle-exclamation',
    ][$ticket->category] ?? 'fa-ticket';
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Ticket Details
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950 break-words">
                {{ $ticket->subject }}
            </h1>

            <p class="text-slate-500 mt-2">
                Customer issue, replies and support resolution details.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 shrink-0">
            <a href="{{ route('tickets.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Back
            </a>

            <a href="{{ route('tickets.edit', $ticket) }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]">
                <i class="fa-regular fa-pen-to-square mr-2 text-xs"></i>
                Edit Ticket
            </a>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

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

    {{-- OVERVIEW STRIP --}}
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-5 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <div class="flex items-start gap-4 min-w-0">
                <div class="h-12 w-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $ticketIcon }} text-sm"></i>
                </div>

                <div class="min-w-0">
                    <h2 class="text-lg font-semibold text-slate-950 truncate">
                        {{ $ticket->subject }}
                    </h2>

                    <p class="text-sm text-slate-500 mt-1 truncate">
                        {{ $ticket->customer->name ?? 'No customer linked' }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>

                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $categoryClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->category)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 xl:min-w-[560px]">
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Assigned To
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 truncate">
                        {{ $ticket->assignedUser->name ?? 'Unassigned' }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Replies
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $ticket->replies->count() }}
                    </p>
                </div>

                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                        Created
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y') }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT MAIN --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- TICKET INFORMATION --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Ticket Information
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Main issue details, ownership and support timestamps.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Subject
                        </p>
                        <p class="font-semibold text-slate-900 mt-2 break-words">
                            {{ $ticket->subject }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Customer
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $ticket->customer->name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Assigned To
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $ticket->assignedUser->name ?? 'Unassigned' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created By
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $ticket->createdBy->name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Created At
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Closed At
                        </p>
                        <p class="font-semibold text-slate-900 mt-2">
                            {{ $ticket->closed_at ? $ticket->closed_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '-' }}
                        </p>
                    </div>

                </div>

                <div class="mt-6 rounded-2xl border border-slate-100 bg-white/70 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Description
                    </p>

                    <p class="mt-3 text-sm text-slate-600 leading-7 whitespace-pre-line">
                        {{ $ticket->description }}
                    </p>
                </div>
            </div>

            {{-- REPLIES --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Ticket Replies
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Conversation and updates for this support ticket.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $ticket->replies->count() }} Replies
                    </span>
                </div>

                {{-- ADD REPLY --}}
                @if($ticket->status !== 'closed')
                    <form method="POST" action="{{ route('tickets.replies.store', $ticket) }}" class="mb-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Add Reply
                            </label>

                            <textarea 
                                name="message"
                                rows="4"
                                placeholder="Write your reply or update..."
                                class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                            >{{ old('message') }}</textarea>
                        </div>

                        <div class="flex justify-end mt-3">
                            <button 
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                            >
                                <i class="fa-solid fa-reply mr-2 text-xs"></i>
                                Add Reply
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-600">
                        <i class="fa-solid fa-lock mr-2 text-xs"></i>
                        This ticket is closed. Replies are disabled.
                    </div>
                @endif

                {{-- REPLY LIST --}}
                <div class="space-y-4">
                    @forelse($ticket->replies->sortByDesc('created_at') as $reply)
                        <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                            <div class="flex items-start justify-between gap-4">

                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ $reply->user ? strtoupper(substr($reply->user->name, 0, 1)) : 'U' }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm text-slate-700 leading-7 whitespace-pre-line">
                                            {{ $reply->message }}
                                        </p>

                                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                            <span class="font-semibold text-slate-600">
                                                {{ $reply->user->name ?? '-' }}
                                            </span>

                                            <span>•</span>

                                            <span>
                                                {{ ucwords(str_replace('_', ' ', $reply->user->role ?? 'user')) }}
                                            </span>

                                            <span>•</span>

                                            <span>
                                                {{ $reply->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->role === 'admin' || $reply->user_id === auth()->id())
                                    <form method="POST" action="{{ route('tickets.replies.destroy', $reply) }}"
                                          onsubmit="return confirm('Delete this reply?')"
                                          class="shrink-0">
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            type="submit"
                                            title="Delete Reply"
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
                                <i class="fa-regular fa-comments"></i>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-slate-900">
                                No replies added yet
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Add the first reply or support update from the form above.
                            </p>
                        </div>
                    @endforelse
                </div>

            </div>
            
            {{-- AI --}}
            <div class="mb-6 rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                {{-- HEADER --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">

                    <div class="flex items-start gap-3 min-w-0">
                        <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-message text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                                AI Reply Suggestion
                            </h2>

                            <p class="text-sm text-slate-500 mt-1 leading-6">
                                Generate a professional support reply based on ticket details.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ai.tickets.reply', $ticket) }}" class="shrink-0">
                        @csrf

                        <button 
                            type="submit"
                            class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                        >
                            <i class="fa-solid fa-wand-magic-sparkles mr-2 text-xs"></i>
                            Suggest Reply
                        </button>
                    </form>

                </div>

                {{-- BODY --}}
                <div class="px-5 sm:px-6 py-5">

                    @if(session('ai_ticket_reply'))
                        <div class="rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-4">
                            <div class="flex items-start gap-3">

                                <div class="h-9 w-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-robot text-xs"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700 mb-2">
                                        Suggested Reply
                                    </p>

                                    <p class="text-sm text-slate-700 leading-7 whitespace-pre-line">
                                        {{ session('ai_ticket_reply') }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-8 text-center">
                            <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                <i class="fa-solid fa-comments text-sm"></i>
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-slate-900">
                                No reply suggestion generated yet
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Click suggest reply to create a professional support response.
                            </p>
                        </div>
                    @endif

                </div>

            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="space-y-6">

            {{-- STATUS CARD --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Ticket Status
                </h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Priority</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Category</p>
                        <span class="inline-flex mt-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $categoryClass }}">
                            {{ ucwords(str_replace('_', ' ', $ticket->category)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- CUSTOMER CARD --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Customer
                </h2>

                <div class="mt-5 flex items-start gap-3">
                    <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                        {{ $ticket->customer ? strtoupper(substr($ticket->customer->name, 0, 1)) : '-' }}
                    </div>

                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 truncate">
                            {{ $ticket->customer->name ?? '-' }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1 truncate">
                            {{ $ticket->customer->phone ?? '-' }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1 truncate">
                            {{ $ticket->customer->email ?? '-' }}
                        </p>
                    </div>
                </div>

                @if($ticket->customer)
                    <a href="{{ route('customers.show', $ticket->customer) }}"
                       class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                        <i class="fa-regular fa-address-card mr-2 text-xs"></i>
                        View Customer
                    </a>
                @endif
            </div>

            {{-- SUPPORT INFO --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-6 shadow-sm">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Support Info
                </h2>

                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-slate-500">Replies</span>
                        <span class="font-semibold text-slate-900">{{ $ticket->replies->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-slate-500">Assigned Agent</span>
                        <span class="font-semibold text-slate-900 text-right truncate">
                            {{ $ticket->assignedUser->name ?? 'Unassigned' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-slate-500">Last Updated</span>
                        <span class="font-semibold text-slate-900">
                            {{ $ticket->updated_at->timezone('Asia/Kolkata')->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection