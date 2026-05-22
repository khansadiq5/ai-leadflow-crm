<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Task;
use App\Models\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        //Support Agent Dashboard
        if ($user->role === 'support_agent') {
            $ticketQuery = Ticket::query()->where('assigned_to', $user->id);

            $myTickets = (clone $ticketQuery)->count();

            $openTickets = (clone $ticketQuery)
                ->whereIn('status', ['open', 'in_progress'])
                ->count();

            $urgentTickets = (clone $ticketQuery)
                ->where('priority', 'urgent')
                ->whereNotIn('status', ['resolved', 'closed'])
                ->count();

            $resolvedTickets = (clone $ticketQuery)
                ->where('status', 'resolved')
                ->count();

            $closedTickets = (clone $ticketQuery)
                ->where('status', 'closed')
                ->count();

            $recentTickets = (clone $ticketQuery)
                ->with(['customer', 'createdBy'])
                ->latest()
                ->take(6)
                ->get();

            return view('dashboard.index', compact(
                'myTickets',
                'openTickets',
                'urgentTickets',
                'resolvedTickets',
                'closedTickets',
                'recentTickets'
            ))->with('isSupportAgent', true);
        }

        // Sales/Admin/Manager Dashboard
        $leadQuery = Lead::query();
        $customerQuery = Customer::query();
        $dealQuery = Deal::query();
        $taskQuery = Task::query();
        $ticketQuery = Ticket::query();

        if ($user->role === 'sales_executive') {
            $leadQuery->where('assigned_to', $user->id);
            $customerQuery->where('assigned_to', $user->id);
            $dealQuery->where('assigned_to', $user->id);
            $taskQuery->where('assigned_to', $user->id);
        }

        $totalLeads = (clone $leadQuery)->count();
        $totalCustomers = (clone $customerQuery)->count();
        $totalDeals = (clone $dealQuery)->count();
        $totalTasks = (clone $taskQuery)->count();

        $hotLeads = (clone $leadQuery)
            ->where('priority', 'hot')
            ->count();

        $openDeals = (clone $dealQuery)
            ->whereNotIn('stage', ['won', 'lost'])
            ->count();

        $wonRevenue = (clone $dealQuery)
            ->where('stage', 'won')
            ->sum('amount');

        $pendingTasks = (clone $taskQuery)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        $todayFollowups = (clone $taskQuery)
            ->whereDate('due_date', Carbon::today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $overdueTasks = (clone $taskQuery)
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $totalTickets = $user->role === 'sales_executive'
            ? 0
            : (clone $ticketQuery)->count();

        $openTickets = $user->role === 'sales_executive'
            ? 0
            : (clone $ticketQuery)->whereIn('status', ['open', 'in_progress'])->count();

        $urgentTickets = $user->role === 'sales_executive'
            ? 0
            : (clone $ticketQuery)
                ->where('priority', 'urgent')
                ->whereNotIn('status', ['resolved', 'closed'])
                ->count();

        $resolvedTickets = $user->role === 'sales_executive'
            ? 0
            : (clone $ticketQuery)->where('status', 'resolved')->count();

        $recentLeads = (clone $leadQuery)
            ->with('assignedUser')
            ->latest()
            ->take(5)
            ->get();

        $recentDeals = (clone $dealQuery)
            ->with(['customer', 'assignedUser'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingTasks = (clone $taskQuery)
            ->with(['assignedUser', 'lead', 'customer', 'deal'])
            ->where('due_date', '>=', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $recentTickets = $user->role === 'sales_executive'
            ? collect()
            : (clone $ticketQuery)
                ->with(['customer', 'assignedUser'])
                ->latest()
                ->take(5)
                ->get();

        return view('dashboard.index', compact(
            'totalLeads',
            'totalCustomers',
            'totalDeals',
            'totalTasks',
            'hotLeads',
            'openDeals',
            'wonRevenue',
            'pendingTasks',
            'todayFollowups',
            'overdueTasks',
            'totalTickets',
            'openTickets',
            'urgentTickets',
            'resolvedTickets',
            'recentLeads',
            'recentDeals',
            'upcomingTasks',
            'recentTickets'
        ))->with('isSupportAgent', false);
    }
}