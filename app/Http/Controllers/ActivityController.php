<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Task;

class ActivityController extends Controller
{
    public function lead(Lead $lead)
    {
        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $activities = $lead->activities()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('activities.lead', compact('lead', 'activities'));
    }

    public function customer(Customer $customer)
    {
        if (auth()->user()->role === 'sales_executive' && $customer->assigned_to !== auth()->id()) {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $activities = $customer->activities()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('activities.customer', compact('customer', 'activities'));
    }

    public function deal(Deal $deal)
    {
        if (auth()->user()->role === 'sales_executive' && $deal->assigned_to !== auth()->id()) {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $activities = $deal->activities()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('activities.deal', compact('deal', 'activities'));
    }

    public function task(Task $task)
    {
        if(auth()->user()->role === 'sales_executive' && $task->assigned_to !== auth()->id()){
            abort(403);
        }

        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $activities = $task->activities()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('activities.task', compact('task', 'activities'));
    }
}