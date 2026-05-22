<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | LeadFlow CRM</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .bg-pattern {
            background-image:
                radial-gradient(circle at 18% 18%, rgba(15, 23, 42, 0.08), transparent 28%),
                radial-gradient(circle at 88% 12%, rgba(14, 165, 233, 0.10), transparent 30%),
                radial-gradient(circle at 52% 92%, rgba(16, 185, 129, 0.10), transparent 32%);
        }
    </style>
</head>

<body class="min-h-screen bg-[#f6f7fb] text-slate-900">

<section class="min-h-screen bg-pattern grid grid-cols-1 lg:grid-cols-[0.88fr_1fr]">

    <!-- LEFT REGISTER FORM -->
    <div class="flex items-center justify-center px-4 sm:px-8 lg:px-12 xl:px-16 py-10">

        <div class="w-full max-w-[460px]">

            <!-- Mobile Brand -->
            <div class="lg:hidden mb-10">
                <h1 class="text-3xl font-bold tracking-tight">LeadFlow</h1>
                <p class="text-sm text-slate-500 mt-1">CRM System</p>
            </div>

            <div class="mb-9">
                <p class="text-sm font-semibold text-slate-500 mb-3">
                    Create workspace
                </p>
                <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">
                    Register
                </h2>
                <p class="text-slate-500 mt-4 leading-7">
                    Start your CRM workspace and manage leads, customers, deals and follow-ups from one place.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-sm p-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Full Name
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3.5 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Enter your full name"
                    >
                </div>

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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            name="password"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3.5 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                            placeholder="Password"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Confirm
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3.5 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                            placeholder="Confirm"
                        >
                    </div>
                </div>

                <button 
                    type="submit"
                    class="w-full rounded-xl bg-slate-950 text-white py-3.5 font-semibold transition hover:bg-slate-700 active:scale-[0.99]"
                >
                    Create Workspace
                </button>
            </form>

            <div class="my-7 h-px w-full bg-slate-200"></div>

            <p class="text-sm text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-slate-950 font-semibold hover:underline">
                    Login
                </a>
            </p>

        </div>

    </div>

    <!-- RIGHT CONTENT -->
    <div class="hidden lg:flex flex-col justify-between px-12 xl:px-16 py-12 border-l border-white/60">

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
                Simple CRM for modern teams
            </span>

            <h2 class="mt-7 text-5xl xl:text-6xl font-semibold leading-[1.05] tracking-tight">
                Build a cleaner sales process from day one.
            </h2>

            <p class="mt-6 text-lg text-slate-500 leading-8">
                Create your workspace, add leads, assign follow-ups and keep your team focused on the next action.
            </p>

            <div class="mt-10 max-w-xl rounded-[2rem] border border-white/70 bg-white/60 backdrop-blur px-6 py-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="mt-1 h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                        <span class="text-lg font-semibold">L</span>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            Everything starts with your first lead
                        </p>

                        <p class="mt-2 text-sm text-slate-500 leading-6">
                            LeadFlow keeps your contacts, sales stages, notes and pending tasks connected in one simple dashboard.
                        </p>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 border border-slate-200">
                                Contacts
                            </span>

                            <span class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 border border-slate-200">
                                Lead Notes
                            </span>

                            <span class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 border border-slate-200">
                                Sales Tasks
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-sm text-slate-400">
            © {{ date('Y') }} LeadFlow CRM. All rights reserved.
        </p>

    </div>

</section>

</body>
</html>