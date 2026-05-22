<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $settings = [
            'company_name' => setting('company_name', 'LeadFlow CRM'),
            'company_email' => setting('company_email', 'admin@leadflow.com'),
            'company_phone' => setting('company_phone', '+91 98765 43210'),
            'company_address' => setting('company_address', 'India'),

            'crm_name' => setting('crm_name', 'LeadFlow CRM'),
            'currency_symbol' => setting('currency_symbol', '₹'),

            'default_lead_status' => setting('default_lead_status', 'new'),
            'default_lead_priority' => setting('default_lead_priority', 'warm'),

            'task_reminder_enabled' => setting('task_reminder_enabled', '1'),
            'task_reminder_minutes' => setting('task_reminder_minutes', '15'),
            'overdue_alert_enabled' => setting('overdue_alert_enabled', '1'),

            'default_ticket_status' => setting('default_ticket_status', 'open'),
            'default_ticket_priority' => setting('default_ticket_priority', 'medium'),

            'pdf_report_title' => setting('pdf_report_title', 'CRM Performance Report'),
            'pdf_footer_text' => setting('pdf_footer_text', 'This report was generated automatically by LeadFlow CRM.'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'company_name' => 'required|string|max:150',
            'company_email' => 'nullable|email|max:150',
            'company_phone' => 'nullable|string|max:30',
            'company_address' => 'nullable|string|max:500',

            'crm_name' => 'required|string|max:150',
            'currency_symbol' => 'required|string|max:10',

            'default_lead_status' => 'required|in:new,contacted,qualified,converted,lost',
            'default_lead_priority' => 'required|in:hot,warm,cold',

            'task_reminder_enabled' => 'required|in:0,1',
            'task_reminder_minutes' => 'required|integer|min:1|max:1440',
            'overdue_alert_enabled' => 'required|in:0,1',

            'default_ticket_status' => 'required|in:open,in_progress,resolved,closed',
            'default_ticket_priority' => 'required|in:low,medium,high,urgent',

            'pdf_report_title' => 'required|string|max:150',
            'pdf_footer_text' => 'nullable|string|max:500',
        ]);

        foreach ($request->except('_token', '_method') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );

            Cache::forget('setting_' . $key);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}