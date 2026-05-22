<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Lead;
use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $req)
    {
        $query = Customer::with(['assignedUser', 'createdBy']);

        if(auth()->user()->role === 'sales_executive'){
            $query->where('assigned_to', auth()->id());
        }

        if(auth()->user()->role === 'support_agent'){
            abort(403);
        }

        if($req->filled('search')){
            $query->where(function ($q) use ($req){
                $q->where('name', 'like', '%'. $req->search .'%')
                  ->orWhere('email', 'like', '%'. $req->search .'%')
                  ->orWhere('phone', 'like', '%'. $req->search .'%')
                  ->orWhere('company_name', 'like', '%'. $req->search .'%')
                  ->orWhere('city', 'like', '%'. $req->search .'%');
            });
        }

        if($req->filled('type')){
            $query->where('type', $req->type);
        }

        if($req->filled('status')){
            $query->where('status', $req->status);
        }

        $customers = $query->latest()->paginate(5);

        return view('customers.index', compact('customers'));

    }

    public function create(Request $req)
    {
        if(auth()->user()->role === 'support_agent'){
            abort(403);
        }

        $users = User::whereIn('role', ['manager', 'sales_executive'])
            ->where('status', 'active')->get();

        return view('customers.create', compact('users'));    

    }

    public function store(Request $request)
    {
        if(auth()->user()->role === 'support_agent'){
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:150',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'type' => 'required|in:individual,company,vip,regular',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'type' => $request->type,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        ActivityService::log([
            'customer_id' => $customer->id,
            'type' => 'customer_created',
            'description' => auth()->user()->name . ' created this customer.',
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $customer->assigned_to !== auth()->id()) {
            abort(403);
        }

        $customer->load(['assignedUser', 'createdBy', 'customerNotes.user', 'activities.user']);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $customer->assigned_to !== auth()->id()) {
            abort(403);
        }

        $users = User::whereIn('role', ['manager', 'sales_executive'])
            ->where('status', 'active')->get();

        return view('customers.edit', compact('customer', 'users'));
    }

    public function update(Request $request, Customer $customer)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $customer->assigned_to !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:150',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'type' => 'required|in:individual,company,vip,regular',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $customer->status;
        $oldAssignedTo = $customer->assigned_to;

        $newAssignedTo = auth()->user()->role === 'sales_executive'
            ? $customer->assigned_to
            : $request->assigned_to;

        $customer->update([
            'assigned_to' => $newAssignedTo,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'type' => $request->type,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        ActivityService::log([
            'customer_id' => $customer->id,
            'type' => 'customer_updated',
            'description' => auth()->user()->name . ' updated this customer.',
        ]);

        if ($oldStatus !== $request->status) {
            ActivityService::log([
                'customer_id' => $customer->id,
                'type' => 'customer_status_changed',
                'description' => auth()->user()->name . ' changed customer status from ' . ucfirst($oldStatus) . ' to ' . ucfirst($request->status) . '.',
            ]);
        }

        if ($oldAssignedTo != $newAssignedTo) {
            ActivityService::log([
                'customer_id' => $customer->id,
                'type' => 'customer_assigned',
                'description' => auth()->user()->name . ' updated customer assignment.',
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
