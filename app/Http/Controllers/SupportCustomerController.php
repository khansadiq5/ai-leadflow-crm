<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SupportCustomerController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'support_agent') {
            abort(403);
        }

        $customerIds = Ticket::where('assigned_to', auth()->id())
            ->pluck('customer_id')
            ->unique();

        $query = Customer::whereIn('id', $customerIds)
            ->withCount([
                'tickets as total_tickets_count' => function ($q) {
                    $q->where('assigned_to', auth()->id());
                },
                'tickets as open_tickets_count' => function ($q) {
                    $q->where('assigned_to', auth()->id())
                      ->whereIn('status', ['open', 'in_progress']);
                },
            ]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('support_customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        if (auth()->user()->role !== 'support_agent') {
            abort(403);
        }

        $hasAssignedTicket = Ticket::where('customer_id', $customer->id)
            ->where('assigned_to', auth()->id())
            ->exists();

        if (!$hasAssignedTicket) {
            abort(403);
        }

        $tickets = Ticket::where('customer_id', $customer->id)
            ->where('assigned_to', auth()->id())
            ->with(['createdBy', 'replies.user'])
            ->latest()
            ->paginate(10);

        $totalTickets = Ticket::where('customer_id', $customer->id)
            ->where('assigned_to', auth()->id())
            ->count();

        $openTickets = Ticket::where('customer_id', $customer->id)
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        $resolvedTickets = Ticket::where('customer_id', $customer->id)
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['resolved', 'closed'])
            ->count();

        return view('support_customers.show', compact(
            'customer',
            'tickets',
            'totalTickets',
            'openTickets',
            'resolvedTickets'
        ));
    }
}