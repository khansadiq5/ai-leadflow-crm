<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Customer;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Sales Executive ko ticket module access nahi
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        $query = Ticket::with(['customer', 'assignedUser', 'createdBy']);

        // Support Agent ko sirf assigned tickets
        if (auth()->user()->role === 'support_agent') {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($customerQuery) use ($request) {
                      $customerQuery->where('name', 'like', '%' . $request->search . '%')
                                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                                    ->orWhere('company_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('assigned_to') && auth()->user()->role !== 'support_agent') {
            $query->where('assigned_to', $request->assigned_to);
        }

        $tickets = $query->latest()->paginate(10);

        $supportAgents = User::where('role', 'support_agent')
            ->where('status', 'active')
            ->get();

        return view('tickets.index', compact('tickets', 'supportAgents'));
    }

    public function create()
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        // Support agent direct ticket create nahi karega, sirf assigned ticket handle karega
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $customers = Customer::where('status', 'active')->latest()->get();

        $supportAgents = User::where('role', 'support_agent')
            ->where('status', 'active')
            ->get();

        return view('tickets.create', compact('customers', 'supportAgents'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:180',
            'category' => 'required|in:technical,billing,general,feature_request,complaint',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'description' => 'required|string',
        ]);

        $closedAt = null;

        if (in_array($request->status, ['resolved', 'closed'])) {
            $closedAt = now();
        }

        $ticket = Ticket::create([
            'customer_id' => $request->customer_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'description' => $request->description,
            'closed_at' => $closedAt,
        ]);

        if ($ticket->assigned_to) {
            NotificationService::send([
                'user_id' => $ticket->assigned_to,
                'title' => 'New Ticket Assigned',
                'message' => 'A support ticket has been assigned to you: ' . $ticket->subject,
                'type' => 'ticket_assigned',
                'url' => route('tickets.show', $ticket),
            ]);
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent' && $ticket->assigned_to !== auth()->id()) {
            abort(403);
        }

        $ticket->load([
            'customer',
            'assignedUser',
            'createdBy',
            'replies.user'
        ]);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent' && $ticket->assigned_to !== auth()->id()) {
            abort(403);
        }

        $customers = Customer::where('status', 'active')->latest()->get();

        $supportAgents = User::where('role', 'support_agent')
            ->where('status', 'active')
            ->get();

        return view('tickets.edit', compact('ticket', 'customers', 'supportAgents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent' && $ticket->assigned_to !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:180',
            'category' => 'required|in:technical,billing,general,feature_request,complaint',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'description' => 'required|string',
        ]);

        $oldStatus = $ticket->status;
        $oldAssignedTo = $ticket->assigned_to;

        // Support agent assigned_to/customer change nahi karega
        $newAssignedTo = auth()->user()->role === 'support_agent'
            ? $ticket->assigned_to
            : $request->assigned_to;

        $newCustomerId = auth()->user()->role === 'support_agent'
            ? $ticket->customer_id
            : $request->customer_id;

        $closedAt = $ticket->closed_at;

        if (in_array($request->status, ['resolved', 'closed']) && $ticket->closed_at === null) {
            $closedAt = now();
        }

        if (!in_array($request->status, ['resolved', 'closed'])) {
            $closedAt = null;
        }

        $ticket->update([
            'customer_id' => $newCustomerId,
            'assigned_to' => $newAssignedTo,
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'description' => $request->description,
            'closed_at' => $closedAt,
        ]);

        if ($oldAssignedTo != $newAssignedTo && $newAssignedTo) {
            NotificationService::send([
                'user_id' => $newAssignedTo,
                'title' => 'Ticket Assigned',
                'message' => 'A support ticket has been assigned to you: ' . $ticket->subject,
                'type' => 'ticket_assigned',
                'url' => route('tickets.show', $ticket),
            ]);
        }

        if ($oldStatus !== $request->status) {
            $notifyUsers = User::whereIn('role', ['admin', 'manager'])
                ->where('status', 'active')
                ->where('id', '!=', auth()->id())
                ->pluck('id')
                ->toArray();

            if ($ticket->created_by && $ticket->created_by !== auth()->id()) {
                $notifyUsers[] = $ticket->created_by;
            }

            $notifyUsers = array_unique($notifyUsers);

            foreach ($notifyUsers as $userId) {
                NotificationService::send([
                    'user_id' => $userId,
                    'title' => 'Ticket Status Updated',
                    'message' => auth()->user()->name . ' changed ticket status from ' . ucwords(str_replace('_', ' ', $oldStatus)) . ' to ' . ucwords(str_replace('_', ' ', $request->status)) . ': ' . $ticket->subject,
                    'type' => 'ticket_status_updated',
                    'url' => route('tickets.show', $ticket),
                ]);
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    public function storeReply(Request $request, Ticket $ticket)
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent' && $ticket->assigned_to !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $notifyUsers = [];

        if ($ticket->created_by && $ticket->created_by !== auth()->id()) {
            $notifyUsers[] = $ticket->created_by;
        }

        if ($ticket->assigned_to && $ticket->assigned_to !== auth()->id()) {
            $notifyUsers[] = $ticket->assigned_to;
        }

        $notifyUsers = array_unique($notifyUsers);

        foreach ($notifyUsers as $userId) {
            NotificationService::send([
                'user_id' => $userId,
                'title' => 'New Ticket Reply',
                'message' => auth()->user()->name . ' replied on ticket: ' . $ticket->subject,
                'type' => 'ticket_reply',
                'url' => route('tickets.show', $ticket),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Reply added successfully.');
    }

    public function destroyReply(TicketReply $reply)
    {
        if (auth()->user()->role !== 'admin' && $reply->user_id !== auth()->id()) {
            abort(403);
        }

        $reply->delete();

        return redirect()->back()
            ->with('success', 'Reply deleted successfully.');
    }
}