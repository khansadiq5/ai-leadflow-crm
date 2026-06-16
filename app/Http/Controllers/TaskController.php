<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;
use App\Services\ActivityService;
use App\Services\NotificationService;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $query = Task::with([
            'assignedUser',
            'createdBy',
            'lead',
            'customer',
            'deal'
        ]);

        // Sales Executive ko sirf apne assigned tasks dikhenge
        if (auth()->user()->role === 'sales_executive') {
            $query->where('assigned_to', auth()->id());
        }

        // Search by task title / lead / customer / deal
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('lead', function ($leadQuery) use ($request) {
                        $leadQuery->where('name', 'like', '%' . $request->search . '%')
                                  ->orWhere('phone', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('customer', function ($customerQuery) use ($request) {
                        $customerQuery->where('name', 'like', '%' . $request->search . '%')
                                      ->orWhere('phone', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('deal', function ($dealQuery) use ($request) {
                        $dealQuery->where('title', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Assigned user filter sirf admin/manager ke liye
        if ($request->filled('assigned_to') && auth()->user()->role !== 'sales_executive') {
            $query->where('assigned_to', $request->assigned_to);
        }

        $tasks = $query->latest()->paginate(10);

        $users = User::whereIn('role', ['manager', 'sales_executive'])
            ->where('status', 'active')
            ->get();

        return view('tasks.index', compact('tasks', 'users'));
    }

    public function create()
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        // Sales Executive task create nahi karega
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        $users = User::whereIn('role', ['manager', 'sales_executive'])
            ->where('status', 'active')
            ->get();

        $leads = Lead::latest()->get();
        $customers = Customer::latest()->get();
        $deals = Deal::latest()->get();

        return view('tasks.create', compact('users', 'leads', 'customers', 'deals'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        // Sales Executive task create nahi karega
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'lead_id' => 'nullable|exists:leads,id',
            'customer_id' => 'nullable|exists:customers,id',
            'deal_id' => 'nullable|exists:deals,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $completedAt = null;

        if ($request->status === 'completed') {
            $completedAt = now();
        }

        $task = Task::create([
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'lead_id' => $request->lead_id,
            'customer_id' => $request->customer_id,
            'deal_id' => $request->deal_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
            'completed_at' => $completedAt,
        ]);

        NotificationService::send([
            'user_id' => $task->assigned_to,
            'title' => 'New Task Assigned',
            'message' => 'A new task has been assigned to you: ' . $task->title,
            'type' => 'task_assigned',
            'url' => route('tasks.show', $task),
        ]);

        ActivityService::log([
            'lead_id' => $task->lead_id,
            'customer_id' => $task->customer_id,
            'deal_id' => $task->deal_id,
            'task_id' => $task->id,
            'type' => 'task_created',
            'description' => auth()->user()->name . ' created a task: ' . $task->title . '.',
        ]);

        if ($request->status === 'completed') {
            ActivityService::log([
                'lead_id' => $task->lead_id,
                'customer_id' => $task->customer_id,
                'deal_id' => $task->deal_id,
                'task_id' => $task->id,
                'type' => 'task_completed',
                'description' => auth()->user()->name . ' marked this task as completed.',
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        // Sales Executive sirf apna assigned task dekh sakta hai
        if (auth()->user()->role === 'sales_executive' && $task->assigned_to !== auth()->id()) {
            abort(403);
        }

        $task->load(['assignedUser', 'createdBy', 'lead', 'customer', 'deal', 'activities.user']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        // Sales Executive ko edit access nahi
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        $users = User::whereIn('role', ['manager', 'sales_executive'])
            ->where('status', 'active')
            ->get();

        $leads = Lead::latest()->get();
        $customers = Customer::latest()->get();
        $deals = Deal::latest()->get();

        return view('tasks.edit', compact('task', 'users', 'leads', 'customers', 'deals'));
    }

    public function update(Request $request, Task $task)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        // Sales Executive ko full task update/edit access nahi
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'lead_id' => 'nullable|exists:leads,id',
            'customer_id' => 'nullable|exists:customers,id',
            'deal_id' => 'nullable|exists:deals,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        // Update se pehle old status save karo
        $oldStatus = $task->status;

        $completedAt = $task->completed_at;

        if ($request->status === 'completed' && $task->completed_at === null) {
            $completedAt = now();
        }

        if ($request->status !== 'completed') {
            $completedAt = null;
        }

        // Agar due_date change ho gayi, to overdue_notified_at aur reminder_sent_at reset karo
        // Taaki nayi due_date ke liye alert/reminder fir se fire ho sake
        $dueDateChanged = $task->due_date->toDateTimeString() !== 
            \Carbon\Carbon::parse($request->due_date)->toDateTimeString();

        $task->update([
            'assigned_to' => $request->assigned_to,
            'lead_id' => $request->lead_id,
            'customer_id' => $request->customer_id,
            'deal_id' => $request->deal_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
            'completed_at' => $completedAt,
            'overdue_notified_at' => $dueDateChanged ? null : $task->overdue_notified_at,
            'reminder_sent_at'    => $dueDateChanged ? null : $task->reminder_sent_at,
        ]);

        ActivityService::log([
            'lead_id' => $task->lead_id,
            'customer_id' => $task->customer_id,
            'deal_id' => $task->deal_id,
            'task_id' => $task->id,
            'type' => 'task_updated',
            'description' => auth()->user()->name . ' updated this task.',
        ]);

        if ($oldStatus !== $request->status) {
            ActivityService::log([
                'lead_id' => $task->lead_id,
                'customer_id' => $task->customer_id,
                'deal_id' => $task->deal_id,
                'task_id' => $task->id,
                'type' => 'task_status_changed',
                'description' => auth()->user()->name . ' changed task status from ' . ucwords(str_replace('_', ' ', $oldStatus)) . ' to ' . ucwords(str_replace('_', ' ', $request->status)) . '.',
            ]);
        }

        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            ActivityService::log([
                'lead_id' => $task->lead_id,
                'customer_id' => $task->customer_id,
                'deal_id' => $task->deal_id,
                'task_id' => $task->id,
                'type' => 'task_completed',
                'description' => auth()->user()->name . ' marked this task as completed.',
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'manager') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $task->assigned_to !== auth()->id()) {
            abort(403);
        }

        if (!in_array(auth()->user()->role, ['admin', 'sales_executive'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $oldStatus = $task->status;

        $completedAt = $task->completed_at;

        if ($request->status === 'completed' && $task->completed_at === null) {
            $completedAt = now();
        }

        if ($request->status !== 'completed') {
            $completedAt = null;
        }

        $task->update([
            'status' => $request->status,
            'completed_at' => $completedAt,
        ]);

        if ($oldStatus !== $request->status) {
            ActivityService::log([
                'lead_id' => $task->lead_id,
                'customer_id' => $task->customer_id,
                'deal_id' => $task->deal_id,
                'task_id' => $task->id,
                'type' => 'task_status_changed',
                'description' => auth()->user()->name . ' changed task status from ' . ucwords(str_replace('_', ' ', $oldStatus)) . ' to ' . ucwords(str_replace('_', ' ', $request->status)) . '.',
            ]);
        }

        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            ActivityService::log([
                'lead_id' => $task->lead_id,
                'customer_id' => $task->customer_id,
                'deal_id' => $task->deal_id,
                'task_id' => $task->id,
                'type' => 'task_completed',
                'description' => auth()->user()->name . ' marked this task as completed.',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Task status updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        // Manager mark complete nahi karega
        if (auth()->user()->role === 'manager') {
            abort(403);
        }

        // Sales Executive sirf apne assigned task ko complete karega
        if (auth()->user()->role === 'sales_executive' && $task->assigned_to !== auth()->id()) {
            abort(403);
        }

        // Sirf admin aur assigned sales executive ko complete allow
        if (!in_array(auth()->user()->role, ['admin', 'sales_executive'])) {
            abort(403);
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        ActivityService::log([
            'lead_id' => $task->lead_id,
            'customer_id' => $task->customer_id,
            'deal_id' => $task->deal_id,
            'task_id' => $task->id,
            'type' => 'task_completed',
            'description' => auth()->user()->name . ' marked this task as completed.',
        ]);

        if ($task->created_by && $task->created_by !== auth()->id()) {
            NotificationService::send([
                'user_id' => $task->created_by,
                'title' => 'Task Completed',
                'message' => auth()->user()->name . ' completed task: ' . $task->title,
                'type' => 'task_completed',
                'url' => route('tasks.show', $task),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Task marked as completed.');
    }
}