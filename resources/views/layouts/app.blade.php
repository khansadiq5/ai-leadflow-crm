<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>LeadFlow CRM | Smart Customer Relationship Management System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=2">

    {{-- BASIC SEO --}}
    <meta name="title" content="LeadFlow CRM | Smart Customer Relationship Management System">
    <meta name="description" content="LeadFlow CRM is a modern customer relationship management system for managing leads, customers, deals, tasks, support tickets, notifications and team workflows.">
    <meta name="keywords" content="LeadFlow CRM, CRM system, lead management, customer management, sales CRM, support ticket system, task management, deal management, customer relationship management">
    <meta name="author" content="LeadFlow CRM">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0f172a">

    {{-- OPEN GRAPH SEO --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="LeadFlow CRM | Smart CRM Dashboard">
    <meta property="og:description" content="Manage leads, customers, deals, tasks, support tickets and team workflows from one clean CRM dashboard.">
    <meta property="og:image" content="{{ asset('images/favicon.png') }}">
    <meta property="og:site_name" content="LeadFlow CRM">

    {{-- TWITTER SEO --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="LeadFlow CRM | Smart CRM Dashboard">
    <meta name="twitter:description" content="A modern CRM system for sales, support and customer management.">
    <meta name="twitter:image" content="{{ asset('images/favicon.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .app-bg {
            background-image:
                radial-gradient(circle at 18% 18%, rgba(15, 23, 42, 0.06), transparent 28%),
                radial-gradient(circle at 88% 12%, rgba(14, 165, 233, 0.08), transparent 30%),
                radial-gradient(circle at 52% 92%, rgba(16, 185, 129, 0.08), transparent 32%);
        }

        .main-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .main-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .main-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .main-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>

<body class="h-screen overflow-hidden bg-[#f6f7fb] text-slate-900">

<div class="h-screen flex overflow-hidden app-bg">

    <!-- MOBILE OVERLAY -->
    <div 
        id="sidebarOverlay"
        onclick="closeSidebar()"
        class="fixed inset-0 z-40 hidden bg-black/40 lg:hidden">
    </div>

    <!-- SIDEBAR -->
    <aside 
        id="sidebar"
        class="fixed lg:static inset-y-0 left-0 z-50 w-72 shrink-0 h-screen border-r border-white/70 bg-white/75 backdrop-blur-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
    >
        <div class="h-full overflow-y-auto">
            @include('layouts.sidebar')
        </div>
    </aside>

    <!-- MAIN AREA -->
    <div class="flex-1 min-w-0 h-screen flex flex-col overflow-hidden">

        <!-- STICKY NAVBAR -->
        <header class="shrink-0 sticky top-0 z-40 border-b border-white/70 bg-white/70 backdrop-blur-xl">
            @include('layouts.navbar')
        </header>

        <!-- SCROLLABLE CENTER CONTENT ONLY -->
        <main class="main-scroll flex-1 overflow-y-auto overflow-x-hidden">
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>

    </div>

</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.remove('hidden');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');

        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }
</script>

@stack('scripts')

</body>
</html>