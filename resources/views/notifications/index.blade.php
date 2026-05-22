@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Notifications
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Notification Center
            </h1>

            <p class="text-slate-500 mt-2">
                View CRM alerts, task reminders and assignment updates.
            </p>
        </div>

        @if($notifications->count() > 0)
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                @method('PATCH')

                <button 
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                >
                    <i class="fa-solid fa-check-double mr-2 text-xs"></i>
                    Mark All Read
                </button>
            </form>
        @endif
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- NOTIFICATIONS CARD -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <!-- CARD HEADER -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    All Notifications
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Recent CRM updates and system alerts.
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ $notifications->total() }} Records
            </span>
        </div>

        <!-- LIST -->
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notification)

                @php
                    $typeClass = match($notification->type) {
                        'task_assigned' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'task_completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'deal_won' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'deal_lost' => 'bg-red-50 text-red-700 border-red-100',
                        'lead_assigned' => 'bg-purple-50 text-purple-700 border-purple-100',
                        'follow_up' => 'bg-amber-50 text-amber-700 border-amber-100',
                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                    };

                    $iconClass = match($notification->type) {
                        'task_assigned' => 'fa-list-check',
                        'task_completed' => 'fa-circle-check',
                        'deal_won' => 'fa-trophy',
                        'deal_lost' => 'fa-circle-xmark',
                        'lead_assigned' => 'fa-user-plus',
                        'follow_up' => 'fa-calendar-check',
                        default => 'fa-bell',
                    };
                @endphp

                <div class="px-5 sm:px-6 py-5 transition {{ $notification->is_read ? 'bg-white/40 hover:bg-white/70' : 'bg-slate-50/80 hover:bg-white' }}">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                        <!-- CONTENT -->
                        <div class="flex gap-4 min-w-0">
                            <div class="h-11 w-11 rounded-2xl {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-slate-950 text-white' }} flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $iconClass }} text-sm"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="font-semibold text-slate-950">
                                        {{ $notification->title }}
                                    </h2>

                                    @if(!$notification->is_read)
                                        <span class="inline-flex rounded-full border border-red-100 bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                                            New
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-slate-600 mt-1 leading-6">
                                    {{ $notification->message }}
                                </p>

                                <div class="flex flex-wrap items-center gap-2 mt-3">
                                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $typeClass }}">
                                        {{ ucwords(str_replace('_', ' ', $notification->type)) }}
                                    </span>

                                    <span class="text-xs text-slate-400">
                                        {{ $notification->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex items-center gap-2 lg:justify-end shrink-0 pl-15 lg:pl-0">

                            @php
                                $onlyMarkReadTypes = [
                                    'follow_up',
                                    'reminder',
                                    'task_reminder',
                                    'overdue',
                                    'overdue_alert',
                                    'task_overdue',
                                ];

                                $hideOpenButton = in_array($notification->type, $onlyMarkReadTypes);
                            @endphp

                            @if(!$notification->is_read)
                                <!-- Mark as Read Button -->
                                <form method="POST" action="{{ route('notifications.markRead', $notification) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button 
                                        type="submit"
                                        title="Mark as Read"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950"
                                    >
                                        <i class="fa-regular fa-circle-check text-sm"></i>
                                    </button>
                                </form>
                            @else
                                @if(!$hideOpenButton && $notification->url)
                                    <!-- Open Notification Button -->
                                    <a 
                                        href="{{ route('notifications.open', $notification) }}"
                                        title="Open Notification"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-950"
                                    >
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>
                                @endif
                            @endif

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('notifications.destroy', $notification) }}"
                                onsubmit="return confirm('Delete this notification?')">
                                @csrf
                                @method('DELETE')

                                <button 
                                    type="submit"
                                    title="Delete Notification"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-700 transition hover:bg-red-100"
                                >
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </form>

                        </div>

                    </div>
                </div>

            @empty

                <div class="px-5 py-16 text-center">
                    <div class="mx-auto h-14 w-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                        <i class="fa-regular fa-bell text-xl"></i>
                    </div>

                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                        No notifications yet
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        CRM alerts, reminders and assignment updates will appear here.
                    </p>
                </div>

            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($notifications->hasPages())
            <div class="border-t border-slate-100 px-5 sm:px-6 py-4">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>

</div>

@endsection