@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Customer Activity
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                {{ $customer->name }}
            </h1>

            <p class="text-slate-500 mt-2">
                Complete activity history and system updates for this customer.
            </p>
        </div>

        <a href="{{ route('customers.show', $customer) }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
            Back to Customer
        </a>
    </div>

    <!-- ACTIVITY LIST -->
    <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    All Activities
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    View every customer update, note, change and system activity.
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ $activities->total() }} Records
            </span>
        </div>

        <div class="px-5 sm:px-6 py-6">

            <div class="space-y-5">
                @forelse($activities as $activity)

                    <div class="relative flex gap-4">
                        <div class="flex flex-col items-center shrink-0">
                            <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock text-xs"></i>
                            </div>

                            @if(!$loop->last)
                                <div class="mt-3 h-full min-h-8 w-px bg-slate-200"></div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0 rounded-2xl border border-slate-100 bg-white/70 px-4 py-4 transition hover:bg-white hover:border-slate-200">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

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

                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-5 py-14 text-center">
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

        @if($activities->hasPages())
            <div class="border-t border-slate-100 px-5 sm:px-6 py-4">
                {{ $activities->links() }}
            </div>
        @endif

    </div>

</div>

@endsection