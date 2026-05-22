<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LeadFlow CRM</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .bg-pattern {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(15, 23, 42, 0.08), transparent 28%),
                radial-gradient(circle at 80% 10%, rgba(14, 165, 233, 0.10), transparent 30%),
                radial-gradient(circle at 50% 90%, rgba(16, 185, 129, 0.10), transparent 32%);
        }
    </style>
</head>

<body class="min-h-screen bg-[#f6f7fb] text-slate-900">

<section class="min-h-screen bg-pattern grid grid-cols-1 lg:grid-cols-[1fr_0.85fr]">

    <!-- LEFT CONTENT -->
    <div class="hidden lg:flex flex-col justify-between px-12 xl:px-16 py-12">

        <div>
            <h1 class="text-2xl font-bold tracking-tight">
                LeadFlow
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                CRM System
            </p>
        </div>

        <div class="max-w-xl">
            <span class="inline-flex rounded-full border border-slate-200 bg-white/70 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
                Sales workspace for growing teams
            </span>

            <h2 class="mt-7 text-5xl xl:text-6xl font-semibold leading-[1.05] tracking-tight">
                Keep every lead moving forward.
            </h2>

            <p class="mt-6 text-lg text-slate-500 leading-8">
                Track customers, follow-ups, deals and daily sales activity from one clear CRM dashboard.
            </p>

            <div class="mt-10 grid grid-cols-3 gap-4 max-w-lg">
                <div class="rounded-3xl bg-white/80 border border-white shadow-sm p-5">
                    <p class="text-3xl font-semibold">128</p>
                    <p class="text-sm text-slate-500 mt-1">Active Leads</p>
                </div>

                <div class="rounded-3xl bg-white/80 border border-white shadow-sm p-5">
                    <p class="text-3xl font-semibold">42</p>
                    <p class="text-sm text-slate-500 mt-1">Open Deals</p>
                </div>

                <div class="rounded-3xl bg-white/80 border border-white shadow-sm p-5">
                    <p class="text-3xl font-semibold">18</p>
                    <p class="text-sm text-slate-500 mt-1">Tasks Due</p>
                </div>
            </div>
        </div>

        <p class="text-sm text-slate-400">
            © {{ date('Y') }} LeadFlow CRM. All rights reserved.
        </p>

    </div>

    <!-- RIGHT LOGIN -->
    <div class="flex items-center justify-center px-4 sm:px-8 lg:px-12 xl:px-16 py-10">

        <div class="w-full max-w-[430px]">

            <!-- Mobile Brand -->
            <div class="lg:hidden mb-10">
                <h1 class="text-3xl font-bold tracking-tight">LeadFlow</h1>
                <p class="text-sm text-slate-500 mt-1">CRM System</p>
            </div>

            <div class="mb-9">
                <p class="text-sm font-semibold text-slate-500 mb-3">
                    Welcome back
                </p>
                <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">
                    Login
                </h2>
                <p class="text-slate-500 mt-4 leading-7">
                    Access your workspace and continue managing your leads, deals and follow-ups.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-sm p-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3.5 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Enter your email"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3.5 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Enter your password"
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full rounded-xl bg-slate-950 text-white py-3.5 font-semibold transition hover:bg-slate-700 active:scale-[0.99]"
                >
                    Login to Dashboard
                </button>
            </form>

            <div class="my-7 h-px w-full bg-slate-200"></div>

            <p class="text-sm text-slate-500">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-slate-950 font-semibold hover:underline">
                    Create an account
                </a>
            </p>

        </div>

    </div>

</section>

</body>
</html>