<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403);
        }

        $fromDate = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : now()->startOfMonth();

        $toDate = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : now()->endOfDay();

        //Base Queries With Date Filter

        $leadQuery = Lead::whereBetween('created_at', [$fromDate, $toDate]);
        $customerQuery = Customer::whereBetween('created_at', [$fromDate, $toDate]);
        $dealQuery = Deal::whereBetween('created_at', [$fromDate, $toDate]);
        $taskQuery = Task::whereBetween('created_at', [$fromDate, $toDate]);
        $ticketQuery = Ticket::whereBetween('created_at', [$fromDate, $toDate]);

        //Lead Reports

        $totalLeads = (clone $leadQuery)->count();

        $hotLeads = (clone $leadQuery)
            ->where('priority', 'hot')
            ->count();

        $warmLeads = (clone $leadQuery)
            ->where('priority', 'warm')
            ->count();

        $coldLeads = (clone $leadQuery)
            ->where('priority', 'cold')
            ->count();

        $convertedLeads = (clone $leadQuery)
            ->where('status', 'converted')
            ->count();

        $leadStatusCounts = [
            'new' => (clone $leadQuery)->where('status', 'new')->count(),
            'contacted' => (clone $leadQuery)->where('status', 'contacted')->count(),
            'qualified' => (clone $leadQuery)->where('status', 'qualified')->count(),
            'converted' => (clone $leadQuery)->where('status', 'converted')->count(),
            'lost' => (clone $leadQuery)->where('status', 'lost')->count(),
        ];

        $leadPriorityCounts = [
            'hot' => $hotLeads,
            'warm' => $warmLeads,
            'cold' => $coldLeads,
        ];

        //Customer Reports

        $totalCustomers = (clone $customerQuery)->count();

        $activeCustomers = (clone $customerQuery)
            ->where('status', 'active')
            ->count();

        $inactiveCustomers = (clone $customerQuery)
            ->where('status', 'inactive')
            ->count();

        //Deal / Revenue Reports

        $totalDeals = (clone $dealQuery)->count();

        $openDeals = (clone $dealQuery)
            ->whereNotIn('stage', ['won', 'lost'])
            ->count();

        $wonDeals = (clone $dealQuery)
            ->where('stage', 'won')
            ->count();

        $lostDeals = (clone $dealQuery)
            ->where('stage', 'lost')
            ->count();

        $wonRevenue = (clone $dealQuery)
            ->where('stage', 'won')
            ->sum('amount');

        $pipelineRevenue = (clone $dealQuery)
            ->whereNotIn('stage', ['won', 'lost'])
            ->sum('amount');

        $dealStageCounts = [
            'new' => (clone $dealQuery)->where('stage', 'new')->count(),
            'qualified' => (clone $dealQuery)->where('stage', 'qualified')->count(),
            'proposal_sent' => (clone $dealQuery)->where('stage', 'proposal_sent')->count(),
            'negotiation' => (clone $dealQuery)->where('stage', 'negotiation')->count(),
            'won' => $wonDeals,
            'lost' => $lostDeals,
        ];

        //Task Reports

        $totalTasks = (clone $taskQuery)->count();

        $pendingTasks = (clone $taskQuery)
            ->where('status', 'pending')
            ->count();

        $inProgressTasks = (clone $taskQuery)
            ->where('status', 'in_progress')
            ->count();

        $completedTasks = (clone $taskQuery)
            ->where('status', 'completed')
            ->count();

        $cancelledTasks = (clone $taskQuery)
            ->where('status', 'cancelled')
            ->count();

        $overdueTasks = (clone $taskQuery)
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $taskStatusCounts = [
            'pending' => $pendingTasks,
            'in_progress' => $inProgressTasks,
            'completed' => $completedTasks,
            'cancelled' => $cancelledTasks,
        ];

        //Ticket Reports

        $totalTickets = (clone $ticketQuery)->count();

        $openTickets = (clone $ticketQuery)
            ->where('status', 'open')
            ->count();

        $inProgressTickets = (clone $ticketQuery)
            ->where('status', 'in_progress')
            ->count();

        $resolvedTickets = (clone $ticketQuery)
            ->where('status', 'resolved')
            ->count();

        $closedTickets = (clone $ticketQuery)
            ->where('status', 'closed')
            ->count();

        $urgentTickets = (clone $ticketQuery)
            ->where('priority', 'urgent')
            ->count();

        $ticketStatusCounts = [
            'open' => $openTickets,
            'in_progress' => $inProgressTickets,
            'resolved' => $resolvedTickets,
            'closed' => $closedTickets,
        ];

        $ticketPriorityCounts = [
            'low' => (clone $ticketQuery)->where('priority', 'low')->count(),
            'medium' => (clone $ticketQuery)->where('priority', 'medium')->count(),
            'high' => (clone $ticketQuery)->where('priority', 'high')->count(),
            'urgent' => $urgentTickets,
        ];

        //Team Performance
        
        $salesUsers = User::whereIn('role', ['sales_executive', 'manager'])
            ->where('status', 'active')
            ->withCount([
                'assignedLeads as assigned_leads_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                },
                'assignedTasks as assigned_tasks_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                },
                'assignedTasks as completed_tasks_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate])
                      ->where('status', 'completed');
                },
            ])
            ->get();

        $supportUsers = User::where('role', 'support_agent')
            ->where('status', 'active')
            ->withCount([
                'assignedTickets as assigned_tickets_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                },
                'assignedTickets as resolved_tickets_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate])
                      ->whereIn('status', ['resolved', 'closed']);
                },
            ])
            ->get();

        //Recent Important Records

        $recentWonDeals = Deal::with(['customer', 'assignedUser'])
            ->where('stage', 'won')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->take(5)
            ->get();

        $recentUrgentTickets = Ticket::with(['customer', 'assignedUser'])
            ->where('priority', 'urgent')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->take(5)
            ->get();

        $recentOverdueTasks = Task::with(['assignedUser', 'lead', 'customer', 'deal'])
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->take(5)
            ->get();

        return view('reports.index', compact(
            'fromDate',
            'toDate',

            'totalLeads',
            'hotLeads',
            'warmLeads',
            'coldLeads',
            'convertedLeads',
            'leadStatusCounts',
            'leadPriorityCounts',

            'totalCustomers',
            'activeCustomers',
            'inactiveCustomers',

            'totalDeals',
            'openDeals',
            'wonDeals',
            'lostDeals',
            'wonRevenue',
            'pipelineRevenue',
            'dealStageCounts',

            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'cancelledTasks',
            'overdueTasks',
            'taskStatusCounts',

            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'urgentTickets',
            'ticketStatusCounts',
            'ticketPriorityCounts',

            'salesUsers',
            'supportUsers',

            'recentWonDeals',
            'recentUrgentTickets',
            'recentOverdueTasks'
        ));
    }

    private function getDateRange($request): array
    {
        $fromDate = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : now()->startOfMonth();

        $toDate = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : now()->endOfDay();

        return [$fromDate, $toDate];
    }

    private function authorizeReports(): void
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403);
        }
    }

    private function downloadCsv(string $filename, array $headers, $rows)
    {
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $headers);

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportLeads(Request $request)
    {
        $this->authorizeReports();

        [$fromDate, $toDate] = $this->getDateRange($request);

        $leads = Lead::with('assignedUser')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->get();

        $headers = [
            'Name',
            'Email',
            'Phone',
            'Company',
            'Source',
            'Interested Service',
            'Budget',
            'Status',
            'Priority',
            'Assigned User',
            'Created Date',
        ];

        $rows = $leads->map(function ($lead) {
            return [
                $lead->name,
                $lead->email,
                $lead->phone,
                $lead->company_name,
                $lead->source,
                $lead->interested_service,
                $lead->budget,
                ucwords(str_replace('_', ' ', $lead->status)),
                ucfirst($lead->priority),
                $lead->assignedUser->name ?? 'Unassigned',
                $lead->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];
        });

        return $this->downloadCsv(
            'leads-report-' . now()->format('Y-m-d-H-i') . '.csv',
            $headers,
            $rows
        );
    }

    public function exportDeals(Request $request)
    {
        $this->authorizeReports();

        [$fromDate, $toDate] = $this->getDateRange($request);

        $deals = Deal::with(['customer', 'assignedUser'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->get();

        $headers = [
            'Title',
            'Customer',
            'Amount',
            'Stage',
            'Probability',
            'Assigned User',
            'Expected Close Date',
            'Closed At',
            'Created Date',
        ];

        $rows = $deals->map(function ($deal) {
            return [
                $deal->title,
                $deal->customer->name ?? '-',
                $deal->amount,
                ucwords(str_replace('_', ' ', $deal->stage)),
                $deal->probability . '%',
                $deal->assignedUser->name ?? 'Unassigned',
                $deal->expected_close_date ? $deal->expected_close_date->format('d M Y') : '-',
                $deal->closed_at ? $deal->closed_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '-',
                $deal->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];
        });

        return $this->downloadCsv(
            'deals-report-' . now()->format('Y-m-d-H-i') . '.csv',
            $headers,
            $rows
        );
    }

    public function exportTasks(Request $request)
    {
        $this->authorizeReports();

        [$fromDate, $toDate] = $this->getDateRange($request);

        $tasks = Task::with(['assignedUser', 'lead', 'customer', 'deal'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->get();

        $headers = [
            'Title',
            'Assigned User',
            'Related Lead',
            'Related Customer',
            'Related Deal',
            'Status',
            'Priority',
            'Due Date',
            'Completed At',
            'Created Date',
        ];

        $rows = $tasks->map(function ($task) {
            return [
                $task->title,
                $task->assignedUser->name ?? 'Unassigned',
                $task->lead->name ?? '-',
                $task->customer->name ?? '-',
                $task->deal->title ?? '-',
                ucwords(str_replace('_', ' ', $task->status)),
                ucfirst($task->priority),
                $task->due_date ? $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '-',
                $task->completed_at ? $task->completed_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '-',
                $task->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];
        });

        return $this->downloadCsv(
            'tasks-report-' . now()->format('Y-m-d-H-i') . '.csv',
            $headers,
            $rows
        );
    }

    public function exportTickets(Request $request)
    {
        $this->authorizeReports();

        [$fromDate, $toDate] = $this->getDateRange($request);

        $tickets = Ticket::with(['customer', 'assignedUser', 'createdBy'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->get();

        $headers = [
            'Subject',
            'Customer',
            'Assigned Agent',
            'Created By',
            'Category',
            'Priority',
            'Status',
            'Created Date',
            'Closed At',
        ];

        $rows = $tickets->map(function ($ticket) {
            return [
                $ticket->subject,
                $ticket->customer->name ?? '-',
                $ticket->assignedUser->name ?? 'Unassigned',
                $ticket->createdBy->name ?? '-',
                ucwords(str_replace('_', ' ', $ticket->category)),
                ucfirst($ticket->priority),
                ucwords(str_replace('_', ' ', $ticket->status)),
                $ticket->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
                $ticket->closed_at ? $ticket->closed_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '-',
            ];
        });

        return $this->downloadCsv(
            'tickets-report-' . now()->format('Y-m-d-H-i') . '.csv',
            $headers,
            $rows
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorizeReports();

        [$fromDate, $toDate] = $this->getDateRange($request);

        /*
        |--------------------------------------------------------------------------
        | Base Queries With Date Filter
        |--------------------------------------------------------------------------
        */

        $leadQuery = Lead::whereBetween('created_at', [$fromDate, $toDate]);
        $customerQuery = Customer::whereBetween('created_at', [$fromDate, $toDate]);
        $dealQuery = Deal::whereBetween('created_at', [$fromDate, $toDate]);
        $taskQuery = Task::whereBetween('created_at', [$fromDate, $toDate]);
        $ticketQuery = Ticket::whereBetween('created_at', [$fromDate, $toDate]);

        /*
        |--------------------------------------------------------------------------
        | Summary Data
        |--------------------------------------------------------------------------
        */

        $totalLeads = (clone $leadQuery)->count();
        $convertedLeads = (clone $leadQuery)->where('status', 'converted')->count();
        $hotLeads = (clone $leadQuery)->where('priority', 'hot')->count();
        $warmLeads = (clone $leadQuery)->where('priority', 'warm')->count();
        $coldLeads = (clone $leadQuery)->where('priority', 'cold')->count();

        $totalCustomers = (clone $customerQuery)->count();
        $activeCustomers = (clone $customerQuery)->where('status', 'active')->count();
        $inactiveCustomers = (clone $customerQuery)->where('status', 'inactive')->count();

        $totalDeals = (clone $dealQuery)->count();
        $openDeals = (clone $dealQuery)->whereNotIn('stage', ['won', 'lost'])->count();
        $wonDeals = (clone $dealQuery)->where('stage', 'won')->count();
        $lostDeals = (clone $dealQuery)->where('stage', 'lost')->count();
        $wonRevenue = (clone $dealQuery)->where('stage', 'won')->sum('amount');
        $pipelineRevenue = (clone $dealQuery)->whereNotIn('stage', ['won', 'lost'])->sum('amount');

        $totalTasks = (clone $taskQuery)->count();
        $pendingTasks = (clone $taskQuery)->where('status', 'pending')->count();
        $inProgressTasks = (clone $taskQuery)->where('status', 'in_progress')->count();
        $completedTasks = (clone $taskQuery)->where('status', 'completed')->count();
        $cancelledTasks = (clone $taskQuery)->where('status', 'cancelled')->count();

        $overdueTasks = (clone $taskQuery)
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $totalTickets = (clone $ticketQuery)->count();
        $openTickets = (clone $ticketQuery)->where('status', 'open')->count();
        $inProgressTickets = (clone $ticketQuery)->where('status', 'in_progress')->count();
        $resolvedTickets = (clone $ticketQuery)->where('status', 'resolved')->count();
        $closedTickets = (clone $ticketQuery)->where('status', 'closed')->count();
        $urgentTickets = (clone $ticketQuery)->where('priority', 'urgent')->count();

        /*
        |--------------------------------------------------------------------------
        | Breakdown Data
        |--------------------------------------------------------------------------
        */

        $leadStatusCounts = [
            'new' => (clone $leadQuery)->where('status', 'new')->count(),
            'contacted' => (clone $leadQuery)->where('status', 'contacted')->count(),
            'qualified' => (clone $leadQuery)->where('status', 'qualified')->count(),
            'converted' => $convertedLeads,
            'lost' => (clone $leadQuery)->where('status', 'lost')->count(),
        ];

        $leadPriorityCounts = [
            'hot' => $hotLeads,
            'warm' => $warmLeads,
            'cold' => $coldLeads,
        ];

        $dealStageCounts = [
            'new' => (clone $dealQuery)->where('stage', 'new')->count(),
            'qualified' => (clone $dealQuery)->where('stage', 'qualified')->count(),
            'proposal_sent' => (clone $dealQuery)->where('stage', 'proposal_sent')->count(),
            'negotiation' => (clone $dealQuery)->where('stage', 'negotiation')->count(),
            'won' => $wonDeals,
            'lost' => $lostDeals,
        ];

        $taskStatusCounts = [
            'pending' => $pendingTasks,
            'in_progress' => $inProgressTasks,
            'completed' => $completedTasks,
            'cancelled' => $cancelledTasks,
        ];

        $ticketStatusCounts = [
            'open' => $openTickets,
            'in_progress' => $inProgressTickets,
            'resolved' => $resolvedTickets,
            'closed' => $closedTickets,
        ];

        $ticketPriorityCounts = [
            'low' => (clone $ticketQuery)->where('priority', 'low')->count(),
            'medium' => (clone $ticketQuery)->where('priority', 'medium')->count(),
            'high' => (clone $ticketQuery)->where('priority', 'high')->count(),
            'urgent' => $urgentTickets,
        ];

        /*
        |--------------------------------------------------------------------------
        | Team Performance
        |--------------------------------------------------------------------------
        */

        $salesUsers = User::whereIn('role', ['sales_executive', 'manager'])
            ->where('status', 'active')
            ->withCount([
                'assignedLeads as assigned_leads_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                },
                'assignedTasks as assigned_tasks_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                },
                'assignedTasks as completed_tasks_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate])
                        ->where('status', 'completed');
                },
            ])
            ->get();

        $supportUsers = User::where('role', 'support_agent')
            ->where('status', 'active')
            ->withCount([
                'assignedTickets as assigned_tickets_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                },
                'assignedTickets as resolved_tickets_count' => function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate])
                        ->whereIn('status', ['resolved', 'closed']);
                },
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Records
        |--------------------------------------------------------------------------
        */

        $recentWonDeals = Deal::with(['customer', 'assignedUser'])
            ->where('stage', 'won')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->take(5)
            ->get();

        $recentUrgentTickets = Ticket::with(['customer', 'assignedUser'])
            ->where('priority', 'urgent')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->take(5)
            ->get();

        $recentOverdueTasks = Task::with(['assignedUser', 'lead', 'customer', 'deal'])
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PDF Export
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView('reports.pdf', compact(
            'fromDate',
            'toDate',

            'totalLeads',
            'convertedLeads',
            'hotLeads',
            'warmLeads',
            'coldLeads',
            'leadPriorityCounts',

            'totalCustomers',
            'activeCustomers',
            'inactiveCustomers',

            'totalDeals',
            'openDeals',
            'wonDeals',
            'lostDeals',
            'wonRevenue',
            'pipelineRevenue',

            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'cancelledTasks',
            'overdueTasks',

            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'urgentTickets',

            'leadStatusCounts',
            'dealStageCounts',
            'taskStatusCounts',
            'ticketStatusCounts',
            'ticketPriorityCounts',

            'salesUsers',
            'supportUsers',

            'recentWonDeals',
            'recentUrgentTickets',
            'recentOverdueTasks'
        ));

        return $pdf->setPaper('a4', 'portrait')
            ->download('crm-performance-report-' . now()->format('Y-m-d-H-i') . '.pdf');
    }

}