<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\User;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityService;
use App\Services\NotificationService;

class DealController extends Controller
{

    public function index(Request $req)
    {
        if(auth()->user()->role === 'support_agent'){
            abort(403);
        }

        $query = Deal::with(['customer', 'assignedUser', 'createdBy']);

        if(auth()->user()->role === 'sales_executive'){
            $query->where('assigned_to', auth()->id());
        }

        if($req->filled('search')){
            $query->where(function ($q) use ($req){
                $q->where('title', 'like', '%'. $req->search . '%')
                ->orWhereHas('customer', function($customerQuery) use ($req){
                    $customerQuery->where('name', 'like', '%'. $req->search . '%')
                                ->orWhere('company_name', 'like', '%'. $req->search . '%');  
                });
            });
        }

        if($req->filled('stage')){
            $query->where('stage', $req->stage);
        }

        if($req->filled('assigned_to')){
            $query->where('assigned_to', $req->assigned_to);
        }

        $deals = $query->latest()->paginate(10);

        $users = User::whereIn('role', ['manager', 'sales_executive'])
                ->where('status', 'active')
                ->get();
        return view('deals.index', compact('deals', 'users'));
    }

    public function create()
    {
        if(auth()->user()->role === 'support_agent'){
            abort(403);
        }

        $customers = Customer::where('status', 'active')->latest()->get();

        $users = User::whereIn('role', ['manager', 'sales_executive'])
                ->where('status', 'active')
                ->get();

        return view('deals.create', compact('customers', 'users'));        
    }

    public function store(Request $request)
    {
        if(auth()->user()->role === 'support_agent'){
            abort(403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'stage' => 'required|in:new,qualified,proposal_sent,negotiation,won,lost',
            'probability' => 'required|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'lost_reason' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $closedAt = null;

        if (in_array($request->stage, ['won', 'lost'])) {
            $closedAt = now();
        }

        $deal = Deal::create([
            'customer_id' => $request->customer_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'stage' => $request->stage,
            'probability' => $request->probability,
            'expected_close_date' => $request->expected_close_date,
            'closed_at' => $closedAt,
            'lost_reason' => $request->lost_reason,
            'description' => $request->description,
        ]);

        ActivityService::log([
            'customer_id' => $deal->customer_id,
            'deal_id' => $deal->id,
            'type' => 'deal_created',
            'description' => auth()->user()->name . ' created a deal: ' . $deal->title . '.',
        ]);

        return redirect()->route('deals.index')
            ->with('success', 'Deal created successfully.');
    }

    public function show(Deal $deal)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $deal->assigned_to !== auth()->id()) {
            abort(403);
        }

        $deal->load(['customer', 'assignedUser', 'createdBy', 'notes.user', 'activities.user']);

        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $deal->assigned_to !== auth()->id()) {
            abort(403);
        }

        $customers = Customer::where('status', 'active')->latest()->get();

        $users = User::whereIn('role', ['manager', 'sales_executive'])
            ->where('status', 'active')
            ->get();

        return view('deals.edit', compact('deal', 'customers', 'users'));
    }
    
    public function update(Request $request, Deal $deal)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $deal->assigned_to !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'stage' => 'required|in:new,qualified,proposal_sent,negotiation,won,lost',
            'probability' => 'required|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'lost_reason' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Update se pehle old values save karo
        $oldStage = $deal->stage;

        $closedAt = $deal->closed_at;

        if (in_array($request->stage, ['won', 'lost']) && $deal->closed_at === null) {
            $closedAt = now();
        }

        if (!in_array($request->stage, ['won', 'lost'])) {
            $closedAt = null;
        }

        $newAssignedTo = auth()->user()->role === 'sales_executive'
            ? $deal->assigned_to
            : $request->assigned_to;

        $deal->update([
            'customer_id' => $request->customer_id,
            'assigned_to' => $newAssignedTo,
            'title' => $request->title,
            'amount' => $request->amount,
            'stage' => $request->stage,
            'probability' => $request->probability,
            'expected_close_date' => $request->expected_close_date,
            'closed_at' => $closedAt,
            'lost_reason' => $request->lost_reason,
            'description' => $request->description,
        ]);

        ActivityService::log([
            'customer_id' => $deal->customer_id,
            'deal_id' => $deal->id,
            'type' => 'deal_updated',
            'description' => auth()->user()->name . ' updated this deal.',
        ]);

        if ($oldStage !== $request->stage) {
            ActivityService::log([
                'customer_id' => $deal->customer_id,
                'deal_id' => $deal->id,
                'type' => 'deal_stage_changed',
                'description' => auth()->user()->name . ' changed deal stage from ' . ucwords(str_replace('_', ' ', $oldStage)) . ' to ' . ucwords(str_replace('_', ' ', $request->stage)) . '.',
            ]);
        }

        if ($request->stage === 'won' && $oldStage !== 'won') {
            ActivityService::log([
                'customer_id' => $deal->customer_id,
                'deal_id' => $deal->id,
                'type' => 'deal_won',
                'description' => auth()->user()->name . ' marked this deal as won.',
            ]);

            $notifyUsers = User::whereIn('role', ['admin', 'manager'])
                ->where('status', 'active')
                ->where('id', '!=', auth()->id())
                ->pluck('id')
                ->toArray();

            if ($deal->assigned_to && $deal->assigned_to !== auth()->id()) {
                $notifyUsers[] = $deal->assigned_to;
            }

            $notifyUsers = array_unique($notifyUsers);

            foreach ($notifyUsers as $userId) {
                NotificationService::send([
                    'user_id' => $userId,
                    'title' => 'Deal Won',
                    'message' => auth()->user()->name . ' marked deal as won: ' . $deal->title . ' worth ₹' . number_format($deal->amount, 2),
                    'type' => 'deal_won',
                    'url' => route('deals.show', $deal),
                ]);
            }
        }

        if ($request->stage === 'lost' && $oldStage !== 'lost') {
            ActivityService::log([
                'customer_id' => $deal->customer_id,
                'deal_id' => $deal->id,
                'type' => 'deal_lost',
                'description' => auth()->user()->name . ' marked this deal as lost.',
            ]);
        }

        return redirect()->route('deals.index')
            ->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $deal->delete();

        return redirect()->route('deals.index')
            ->with('success', 'Deal deleted successfully.');
    }

    public function pipeline()
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $query = Deal::with(['customer', 'assignedUser']);

        if (auth()->user()->role === 'sales_executive') {
            $query->where('assigned_to', auth()->id());
        }

        $deals = $query->latest()->get()->groupBy('stage');

        $stages = [
            'new' => 'New',
            'qualified' => 'Qualified',
            'proposal_sent' => 'Proposal Sent',
            'negotiation' => 'Negotiation',
            'won' => 'Won',
            'lost' => 'Lost',
        ];
        return view('deals.pipeline', compact('deals', 'stages'));
    }
    
}
