@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                System Configuration
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Settings
            </h1>

            <p class="text-slate-500 mt-2">
                Manage company details, CRM defaults, reminders and report preferences.
            </p>
        </div>

        <div class="inline-flex w-fit items-center gap-3 rounded-xl border border-slate-200 bg-white/80 px-4 py-3 shadow-sm">
            <div class="h-9 w-9 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <div class="leading-tight">
                <p class="text-xs text-slate-500">Access Level</p>
                <p class="text-sm font-semibold text-slate-900">Admin Only</p>
            </div>
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
            <p class="font-semibold mb-2">
                Please fix the following errors:
            </p>

            <div class="space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SETTINGS OVERVIEW --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm text-slate-500">CRM Name</p>
                    <h3 class="text-lg font-semibold text-slate-950 mt-2 truncate">
                        {{ $settings['crm_name'] }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-2">Application branding</p>
                </div>

                <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-emerald-100 bg-emerald-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm text-emerald-700">Task Reminder</p>
                    <h3 class="text-lg font-semibold text-emerald-700 mt-2">
                        {{ $settings['task_reminder_enabled'] == '1' ? 'Enabled' : 'Disabled' }}
                    </h3>
                    <p class="text-xs text-emerald-600/70 mt-2">
                        Before {{ $settings['task_reminder_minutes'] }} minutes
                    </p>
                </div>

                <div class="h-11 w-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-bell text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-blue-100 bg-blue-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm text-blue-700">Lead Default</p>
                    <h3 class="text-lg font-semibold text-blue-700 mt-2">
                        {{ ucwords(str_replace('_', ' ', $settings['default_lead_status'])) }}
                    </h3>
                    <p class="text-xs text-blue-600/70 mt-2">
                        {{ ucfirst($settings['default_lead_priority']) }} priority
                    </p>
                </div>

                <div class="h-11 w-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-red-100 bg-red-50/70 backdrop-blur px-5 py-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm text-red-700">Ticket Default</p>
                    <h3 class="text-lg font-semibold text-red-700 mt-2">
                        {{ ucwords(str_replace('_', ' ', $settings['default_ticket_status'])) }}
                    </h3>
                    <p class="text-xs text-red-600/70 mt-2">
                        {{ ucfirst($settings['default_ticket_priority']) }} priority
                    </p>
                </div>

                <div class="h-11 w-11 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-sm"></i>
                </div>
            </div>
        </div>

    </div>

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- COMPANY SETTINGS --}}
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 px-5 sm:px-6 py-5">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                        Company Settings
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        These details will be used in reports, headers and future email templates.
                    </p>
                </div>

                <div class="h-10 w-10 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-building text-sm"></i>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>

                        <input 
                            type="text"
                            name="company_name"
                            value="{{ old('company_name', $settings['company_name']) }}"
                            placeholder="Enter company name"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Company Email
                        </label>

                        <input 
                            type="email"
                            name="company_email"
                            value="{{ old('company_email', $settings['company_email']) }}"
                            placeholder="admin@example.com"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Company Phone
                        </label>

                        <input 
                            type="text"
                            name="company_phone"
                            value="{{ old('company_phone', $settings['company_phone']) }}"
                            placeholder="+91 98765 43210"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            CRM Name <span class="text-red-500">*</span>
                        </label>

                        <input 
                            type="text"
                            name="crm_name"
                            value="{{ old('crm_name', $settings['crm_name']) }}"
                            placeholder="LeadFlow CRM"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Company Address
                        </label>

                        <textarea 
                            name="company_address"
                            rows="4"
                            placeholder="Enter company address"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >{{ old('company_address', $settings['company_address']) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- CRM DEFAULTS --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- LEAD SETTINGS --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 sm:px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Lead Defaults
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Default values used while creating new leads.
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Default Lead Status <span class="text-red-500">*</span>
                        </label>

                        <select 
                            name="default_lead_status"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            @foreach(['new','contacted','qualified','converted','lost'] as $status)
                                <option value="{{ $status }}" {{ old('default_lead_status', $settings['default_lead_status']) == $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Default Lead Priority <span class="text-red-500">*</span>
                        </label>

                        <select 
                            name="default_lead_priority"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            @foreach(['hot','warm','cold'] as $priority)
                                <option value="{{ $priority }}" {{ old('default_lead_priority', $settings['default_lead_priority']) == $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-4">
                        <div class="flex items-start gap-3">
                            <div class="h-9 w-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-circle-info text-xs"></i>
                            </div>

                            <p class="text-sm text-blue-700 leading-6">
                                These default values can be used while creating new leads to keep data consistent.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- TICKET SETTINGS --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 sm:px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Ticket Defaults
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Default values used for new support tickets.
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-sm"></i>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Default Ticket Status <span class="text-red-500">*</span>
                        </label>

                        <select 
                            name="default_ticket_status"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            @foreach(['open','in_progress','resolved','closed'] as $status)
                                <option value="{{ $status }}" {{ old('default_ticket_status', $settings['default_ticket_status']) == $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Default Ticket Priority <span class="text-red-500">*</span>
                        </label>

                        <select 
                            name="default_ticket_priority"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            @foreach(['low','medium','high','urgent'] as $priority)
                                <option value="{{ $priority }}" {{ old('default_ticket_priority', $settings['default_ticket_priority']) == $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rounded-2xl border border-red-100 bg-red-50/70 px-4 py-4">
                        <div class="flex items-start gap-3">
                            <div class="h-9 w-9 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            </div>

                            <p class="text-sm text-red-700 leading-6">
                                Ticket defaults help support agents start with consistent status and priority values.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- REMINDER + REPORT SETTINGS --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- REMINDER SETTINGS --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 sm:px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Reminder Settings
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Control task reminder and overdue alert automation.
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-bell text-sm"></i>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Task Reminder Enabled <span class="text-red-500">*</span>
                        </label>

                        <select 
                            name="task_reminder_enabled"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="1" {{ old('task_reminder_enabled', $settings['task_reminder_enabled']) == '1' ? 'selected' : '' }}>
                                Enabled
                            </option>
                            <option value="0" {{ old('task_reminder_enabled', $settings['task_reminder_enabled']) == '0' ? 'selected' : '' }}>
                                Disabled
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Reminder Before Minutes <span class="text-red-500">*</span>
                        </label>

                        <input 
                            type="number"
                            name="task_reminder_minutes"
                            min="1"
                            max="1440"
                            value="{{ old('task_reminder_minutes', $settings['task_reminder_minutes']) }}"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >

                        <p class="text-xs text-slate-400 mt-2">
                            Example: 15 means reminder will be sent 15 minutes before due time.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Overdue Alert Enabled <span class="text-red-500">*</span>
                        </label>

                        <select 
                            name="overdue_alert_enabled"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="1" {{ old('overdue_alert_enabled', $settings['overdue_alert_enabled']) == '1' ? 'selected' : '' }}>
                                Enabled
                            </option>
                            <option value="0" {{ old('overdue_alert_enabled', $settings['overdue_alert_enabled']) == '0' ? 'selected' : '' }}>
                                Disabled
                            </option>
                        </select>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-4 py-4">
                        <div class="flex items-start gap-3">
                            <div class="h-9 w-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock text-xs"></i>
                            </div>

                            <p class="text-sm text-emerald-700 leading-6">
                                Reminder settings improve follow-up consistency and help avoid missed deadlines.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- REPORT SETTINGS --}}
            <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur shadow-sm overflow-hidden">

                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 sm:px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                            Report Settings
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Customize PDF report title, footer and currency.
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-lines text-sm"></i>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Currency Symbol <span class="text-red-500">*</span>
                        </label>

                        <input 
                            type="text"
                            name="currency_symbol"
                            value="{{ old('currency_symbol', $settings['currency_symbol']) }}"
                            placeholder="₹"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            PDF Report Title <span class="text-red-500">*</span>
                        </label>

                        <input 
                            type="text"
                            name="pdf_report_title"
                            value="{{ old('pdf_report_title', $settings['pdf_report_title']) }}"
                            placeholder="CRM Performance Report"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            PDF Footer Text
                        </label>

                        <textarea 
                            name="pdf_footer_text"
                            rows="4"
                            placeholder="This report was generated automatically by LeadFlow CRM."
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >{{ old('pdf_footer_text', $settings['pdf_footer_text']) }}</textarea>
                    </div>

                    <div class="rounded-2xl border border-purple-100 bg-purple-50/70 px-4 py-4">
                        <div class="flex items-start gap-3">
                            <div class="h-9 w-9 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-pdf text-xs"></i>
                            </div>

                            <p class="text-sm text-purple-700 leading-6">
                                Report settings will be used in PDF exports and future analytics documents.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- SUBMIT BAR --}}
        <div class="sticky bottom-4 z-20">
            <div class="rounded-[1.25rem] border border-white/70 bg-white/90 backdrop-blur px-4 sm:px-5 py-4 shadow-lg">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-950">
                            Save CRM Settings
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            Changes will apply to reminders, reports and default forms.
                        </p>
                    </div>

                    <button 
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
                    >
                        <i class="fa-solid fa-floppy-disk mr-2 text-xs"></i>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

@endsection