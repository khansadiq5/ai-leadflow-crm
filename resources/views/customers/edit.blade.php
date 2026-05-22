@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-400 font-semibold mb-3">
                Customer Management
            </p>

            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-950">
                Edit Customer
            </h1>

            <p class="text-slate-500 mt-2">
                Update customer profile, contact details, location and CRM assignment.
            </p>
        </div>

        <a href="{{ route('customers.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
            <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
            Back to Customers
        </a>
    </div>

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

    <!-- FORM -->
    <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- BASIC INFORMATION -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Basic Information
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update the primary customer contact details.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $customer->name) }}"
                        placeholder="Enter customer name"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Phone <span class="text-red-500">*</span>
                    </label>

                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', $customer->phone) }}"
                        placeholder="Enter phone number"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email
                    </label>

                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $customer->email) }}"
                        placeholder="name@example.com"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Company Name
                    </label>

                    <input 
                        type="text" 
                        name="company_name" 
                        value="{{ old('company_name', $customer->company_name) }}"
                        placeholder="Company or organization"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>
            </div>
        </div>

        <!-- LOCATION DETAILS -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Location Details
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update customer address and location information.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        City
                    </label>

                    <input 
                        type="text" 
                        name="city" 
                        value="{{ old('city', $customer->city) }}"
                        placeholder="Enter city"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        State
                    </label>

                    <input 
                        type="text" 
                        name="state" 
                        value="{{ old('state', $customer->state) }}"
                        placeholder="Enter state"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Pincode
                    </label>

                    <input 
                        type="text" 
                        name="pincode" 
                        value="{{ old('pincode', $customer->pincode) }}"
                        placeholder="Enter pincode"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Address
                    </label>

                    <textarea 
                        name="address" 
                        rows="3"
                        placeholder="Enter complete customer address"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- CRM DETAILS -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    CRM Details
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update customer type, account status and team ownership.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Type
                    </label>

                    <select 
                        name="type"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['individual','company','vip','regular'] as $type)
                            <option value="{{ $type }}" {{ old('type', $customer->type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>

                    <select 
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        @foreach(['active','inactive'] as $status)
                            <option value="{{ $status }}" {{ old('status', $customer->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Assign To
                    </label>

                    @if(auth()->user()->role === 'sales_executive')

                        <!-- Sales executive ko naam dikhega, but change nahi kar sakega -->
                        <div class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 cursor-not-allowed">
                            {{ $customer->assignedUser->name ?? 'Unassigned' }}

                            @if($customer->assignedUser)
                                <span class="text-slate-400 font-normal">
                                    - {{ ucwords(str_replace('_', ' ', $customer->assignedUser->role)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Old assigned_to value preserve rahegi -->
                        <input type="hidden" name="assigned_to" value="{{ $customer->assigned_to }}">

                    @else

                        <!-- Admin / Manager ke liye editable select -->
                        <select 
                            name="assigned_to"
                            class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        >
                            <option value="">Unassigned</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $customer->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                </option>
                            @endforeach
                        </select>

                    @endif
                </div>
            </div>
        </div>

        <!-- NOTES -->
        <div class="rounded-[1.5rem] border border-white/70 bg-white/75 backdrop-blur px-5 sm:px-6 py-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold tracking-tight text-slate-950">
                    Notes
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update customer requirements, preferences or internal CRM notes.
                </p>
            </div>

            <textarea 
                name="notes" 
                rows="5"
                placeholder="Add customer notes, communication summary or internal remarks..."
                class="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm outline-none transition focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            >{{ old('notes', $customer->notes) }}</textarea>
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
            <a href="{{ route('customers.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white hover:border-slate-300">
                Cancel
            </a>

            <button 
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-[0.99]"
            >
                Update Customer
            </button>
        </div>

    </form>

</div>

@endsection