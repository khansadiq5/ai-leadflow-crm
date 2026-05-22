@php
    $unreadNotificationsCount = \App\Models\Notification::where('user_id', auth()->id())
        ->where('is_read', false)
        ->count();

    $latestNotifications = \App\Models\Notification::where('user_id', auth()->id())
        ->latest()
        ->take(3)
        ->get();
@endphp

<header class="bg-white border-b border-black/5 px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex items-center justify-between gap-4">

        <!-- LEFT -->
        <div class="flex items-center gap-3 min-w-0">

            <!-- MOBILE MENU BUTTON -->
            <button 
                type="button"
                onclick="toggleSidebar()"
                class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50"
            >
                <i class="fa-solid fa-bars text-sm"></i>
            </button>

            <div class="min-w-0">
                <h2 class="text-xl sm:text-2xl font-semibold tracking-tight text-slate-950 truncate">
                    Dashboard
                </h2>

                <p class="text-xs sm:text-sm text-slate-500 mt-1 truncate max-w-[190px] sm:max-w-none">
                    Welcome back,
                    <span class="font-medium text-slate-700">
                        {{ auth()->user()->name }}
                    </span>

                    <span class="hidden sm:inline">•</span>

                    <span class="hidden sm:inline">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            <!-- MOBILE NOTIFICATION BELL -->
            <a href="{{ route('notifications.index') }}"
               title="Notifications"
               class="lg:hidden relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                <i class="fa-regular fa-bell text-sm"></i>

                @if($unreadNotificationsCount > 0)
                    <span class="absolute -top-1 -right-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1.5 text-[10px] font-semibold leading-none text-white ring-2 ring-white">
                        {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                    </span>
                @endif
            </a>

            <!-- DESKTOP NOTIFICATION DROPDOWN -->
            <div class="relative hidden lg:block group">
                <button
                    type="button"
                    title="Notifications"
                    class="relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 hover:text-slate-950"
                >
                    <i class="fa-regular fa-bell text-sm"></i>

                    @if($unreadNotificationsCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1.5 text-[10px] font-semibold leading-none text-white ring-2 ring-white">
                            {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                        </span>
                    @endif
                </button>

                <!-- DROPDOWN CARD -->
                <div class="invisible absolute right-0 top-12 z-50 w-80 translate-y-2 opacity-0 transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60">

                        <!-- Header -->
                        <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-950">
                                    Notifications
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Recent CRM updates
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if($unreadNotificationsCount > 0)
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                                        {{ $unreadNotificationsCount }} New
                                    </span>
                                @endif

                                <a href="{{ route('notifications.index') }}"
                                   class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-950 hover:text-white hover:border-slate-950">
                                    View All
                                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>

                        <!-- List -->
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($latestNotifications as $notification)

                                @php
                                    $notificationIcon = match($notification->type) {
                                        'task_assigned' => 'fa-list-check',
                                        'task_completed' => 'fa-circle-check',
                                        'deal_won' => 'fa-trophy',
                                        'deal_lost' => 'fa-circle-xmark',
                                        'lead_assigned' => 'fa-user-plus',
                                        'follow_up' => 'fa-calendar-check',
                                        default => 'fa-bell',
                                    };
                                @endphp

                                <a href="{{ route('notifications.index') }}"
                                   class="flex gap-3 px-4 py-3 transition hover:bg-slate-50 {{ $notification->is_read ? 'bg-white' : 'bg-slate-50/80' }}">
                                    
                                    <div class="h-10 w-10 rounded-xl {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-slate-950 text-white' }} flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $notificationIcon }} text-xs"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-sm font-semibold text-slate-900 truncate">
                                                {{ $notification->title }}
                                            </p>

                                            @if(!$notification->is_read)
                                                <span class="mt-1 h-2 w-2 rounded-full bg-red-600 shrink-0"></span>
                                            @endif
                                        </div>

                                        <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                            {{ $notification->message }}
                                        </p>

                                        <p class="mt-2 text-[11px] text-slate-400">
                                            {{ $notification->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                        </p>
                                    </div>
                                </a>

                            @empty
                                <div class="px-4 py-8 text-center">
                                    <div class="mx-auto h-11 w-11 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <i class="fa-regular fa-bell"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-slate-900">
                                        No notifications
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        New CRM alerts will appear here.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            <!-- USER CARD ONLY DESKTOP -->
            <div class="hidden lg:flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2">
                <div class="h-8 w-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="leading-tight min-w-0">
                    <p class="text-xs font-semibold text-slate-800 max-w-[130px] truncate">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-[11px] text-slate-500 truncate">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    </p>
                </div>
            </div>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button 
                    type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-full bg-slate-950 px-3 sm:px-4 text-sm font-medium text-white transition hover:bg-slate-700 active:scale-[0.98]"
                >
                    <span class="hidden sm:inline">Logout</span>
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </button>
            </form>

        </div>

    </div>
</header>