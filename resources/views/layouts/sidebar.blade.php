<div class="h-screen flex flex-col bg-slate-950 text-white">

    <!-- BRAND -->
    <div class="shrink-0 px-5 sm:px-6 py-5 border-b border-white/10">
        <div class="flex items-center justify-between gap-3">

            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    LeadFlow
                </h1>

                <p class="text-xs text-white/45 mt-1">
                    CRM System
                </p>
            </div>

            <!-- MOBILE CLOSE BUTTON -->
            <button 
                type="button"
                onclick="closeSidebar()"
                class="lg:hidden inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white/80 transition hover:bg-white/15 hover:text-white"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>
    </div>

    <!-- NAVIGATION -->
    <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1">

        <a href="{{ route('dashboard') }}"
           onclick="closeSidebar()"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
           {{ request()->routeIs('dashboard') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-chart-line w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->role == 'admin')

            <div class="pt-5 pb-2 px-4">
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/35">
                    Administration
                </p>
            </div>

            <a href="{{ route('users.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('users.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-5"></i>
                <span>Users</span>
            </a>

            <a href="{{ route('leads.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('leads.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-user-plus w-5 text-center"></i>
                <span>Leads</span>
            </a>

            <a href="{{ route('customers.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('customers.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('deals.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('deals.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-handshake w-5 text-center"></i>
                <span>Deals</span>
            </a>

            <a href="{{ route('tasks.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('tasks.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-list-check w-5 text-center"></i>
                <span>Tasks</span>
            </a>

            <a href="{{ route('tickets.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('tickets.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-ticket w-5 text-center"></i>
                <span>Tickets</span>
            </a>

            <div class="pt-5 pb-2 px-4">
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/35">
                    Insights
                </p>
            </div>

            <a href="{{ route('reports.index') }}"
            onclick="closeSidebar()"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('reports.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5"></i>
                Reports
            </a>


            <a href="{{ route('settings.index') }}"
               onclick="closeSidebar()"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('settings.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-gear w-5 text-center"></i>
                <span>Settings</span>
            </a>

        @endif

        @if(auth()->user()->role == 'manager')

            <div class="pt-5 pb-2 px-4">
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/35">
                    Team Workspace
                </p>
            </div>

            <a href="{{ route('leads.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('leads.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-user-plus w-5 text-center"></i>
                <span>Team Leads</span>
            </a>

            <a href="{{ route('customers.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('customers.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span>Team Customers</span>
            </a>

            <a href="{{ route('deals.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('deals.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-handshake w-5 text-center"></i>
                <span>Team Deals</span>
            </a>

            <a href="{{ route('tasks.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('tasks.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-list-check w-5 text-center"></i>
                <span>Team Tasks</span>
            </a>

            <a href="{{ route('reports.index') }}"
            onclick="closeSidebar()"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('reports.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5"></i>
                Reports
            </a>

        @endif

        @if(auth()->user()->role == 'sales_executive')

            <div class="pt-5 pb-2 px-4">
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/35">
                    Sales Workspace
                </p>
            </div>

            <a href="{{ route('leads.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('leads.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-user-plus w-5 text-center"></i>
                <span>My Leads</span>
            </a>

            <a href="{{ route('customers.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('customers.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span>My Customers</span>
            </a>

            <a href="{{ route('deals.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('deals.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-handshake w-5 text-center"></i>
                <span>My Deals</span>
            </a>

            <a href="{{ route('tasks.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('tasks.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-list-check w-5 text-center"></i>
                <span>My Tasks</span>
            </a>

        @endif

        @if(auth()->user()->role == 'support_agent')

            <div class="pt-5 pb-2 px-4">
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/35">
                    Support Desk
                </p>
            </div>

            <a href="{{ route('tickets.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('tickets.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-ticket w-5 text-center"></i>
                <span>My Tickets</span>
            </a>

            <a href="{{ route('support.customers.index') }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('support.customers.*') ? 'bg-white text-slate-950 shadow-sm' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-headset w-5 text-center"></i>
                <span>Support Customers</span>
            </a>

        @endif

    </nav>

    <!-- LOGIN INFO ONLY MOBILE/TABLET -->
    <div class="lg:hidden shrink-0 border-t border-white/10 px-4 py-4">
        <div class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3">
            <div class="h-10 w-10 rounded-xl bg-white text-slate-950 flex items-center justify-center text-sm font-bold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="text-sm font-semibold truncate">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-xs text-white/45 truncate">
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                </p>
            </div>
        </div>
    </div>

</div>
