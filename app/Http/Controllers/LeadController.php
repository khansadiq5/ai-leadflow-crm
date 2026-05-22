<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\ActivityService;
use App\Services\NotificationService;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('assignedUser');

        if (auth()->user()->role === 'sales_executive') {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $leads = $query->latest()->paginate(5);

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['sales_executive', 'manager'])
            ->where('status', 'active')
            ->get();

        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:150',
            'source' => 'nullable|string|max:100',
            'interested_service' => 'nullable|string|max:150',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'priority' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $lead = Lead::create([
        'assigned_to' => $request->assigned_to,
        'created_by' => auth()->id(),
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'company_name' => $request->company_name,
        'source' => $request->source,
        'interested_service' => $request->interested_service,
        'budget' => $request->budget,
        'status' => $request->status,
        'priority' => $request->priority,
        'follow_up_date' => $request->follow_up_date,
        'description' => $request->description,
    ]);

    ActivityService::log([
        'lead_id' => $lead->id,
        'type' => 'lead_created',
        'description' => auth()->user()->name . ' created this lead.',
    ]);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        $lead->load(['assignedUser', 'notes.user', 'activities.user']);

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        $users = User::whereIn('role', ['sales_executive', 'manager'])
            ->where('status', 'active')
            ->get();

        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:150',
            'source' => 'nullable|string|max:100',
            'interested_service' => 'nullable|string|max:150',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'priority' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        // Update se pehle old values save karo
        $oldStatus = $lead->status;
        $oldAssignedTo = $lead->assigned_to;

        // Sales executive assigned_to change nahi kar sakta
        $newAssignedTo = auth()->user()->role === 'sales_executive'
            ? $lead->assigned_to
            : $request->assigned_to;

        $lead->update([
            'assigned_to' => $newAssignedTo,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'source' => $request->source,
            'interested_service' => $request->interested_service,
            'budget' => $request->budget,
            'status' => $request->status,
            'priority' => $request->priority,
            'follow_up_date' => $request->follow_up_date,
            'description' => $request->description,
        ]);

        ActivityService::log([
            'lead_id' => $lead->id,
            'type' => 'lead_updated',
            'description' => auth()->user()->name . ' updated this lead.',
        ]);

        if ($oldStatus !== $request->status) {
            ActivityService::log([
                'lead_id' => $lead->id,
                'type' => 'lead_status_changed',
                'description' => auth()->user()->name . ' changed lead status from ' . ucwords(str_replace('_', ' ', $oldStatus)) . ' to ' . ucwords(str_replace('_', ' ', $request->status)) . '.',
            ]);
        }

        if ($oldAssignedTo != $newAssignedTo) {
            ActivityService::log([
                'lead_id' => $lead->id,
                'type' => 'lead_assigned',
                'description' => auth()->user()->name . ' updated lead assignment.',
            ]);

            if ($newAssignedTo) {
                NotificationService::send([
                    'user_id' => $newAssignedTo,
                    'title' => 'New Lead Assigned',
                    'message' => 'A lead has been assigned to you: ' . $lead->name,
                    'type' => 'lead_assigned',
                    'url' => route('leads.show', $lead),
                ]);
            }
        }

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function convert(Lead $lead)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        if ($lead->status === 'converted' && $lead->converted_customer_id) {
            return redirect()->route('customers.show', $lead->converted_customer_id)
                ->with('success', 'This lead is already converted.');
        }

        $customer = Customer::create([
            'assigned_to' => $lead->assigned_to,
            'created_by' => auth()->id(),
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company_name' => $lead->company_name,
            'type' => 'regular',
            'status' => 'active',
            'notes' => $lead->description,
        ]);

        $lead->update([
            'status' => 'converted',
            'converted_customer_id' => $customer->id,
        ]);

        ActivityService::log([
            'lead_id' => $lead->id,
            'customer_id' => $customer->id,
            'type' => 'lead_converted',
            'description' => auth()->user()->name . ' converted this lead to customer.',
        ]);

        ActivityService::log([
            'customer_id' => $customer->id,
            'lead_id' => $lead->id,
            'type' => 'customer_created',
            'description' => auth()->user()->name . ' created this customer from a lead.',
        ]);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Lead converted to customer successfully.');
    }
}