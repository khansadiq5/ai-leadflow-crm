<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Ticket;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use App\Mail\LeadFollowUpMail;
use Illuminate\Support\Facades\Mail;

class AiController extends Controller
{
    public function leadFollowUp(Lead $lead, GeminiService $gemini)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        $lead->load(['assignedUser']);

        $lastNote = $lead->notes()->latest()->first();

        $crmName = setting('crm_name', 'LeadFlow CRM');
        $companyName = setting('company_name', 'LeadFlow CRM');
        $senderName = auth()->user()->name;

        $lastNote = $lead->notes()->latest()->first();
        $lastNoteText = $lastNote->note ?? $lastNote->content ?? $lastNote->description ?? 'No notes available';

        $prompt = "
        Write a complete CRM follow-up message for this lead.

        Rules:
        - Use real values only.
        - Do not use placeholders or square brackets.
        - No subject line.
        - Hinglish + English mix.
        - Keep it between 130 to 160 words.
        - Message should feel natural, professional and human.
        - Mention the interested service naturally.
        - Mention the company name only if available.
        - Mention budget only if it is available.
        - Add one polite call-to-action asking for a suitable time to discuss.
        - End properly with: Best regards, {$senderName}
        - Do not stop mid-sentence.
        - Return only the final message.

        Lead:
        Name: {$lead->name}
        Company: " . ($lead->company_name ?? 'Not available') . "
        Interested Service: " . ($lead->interested_service ?? 'CRM solution') . "
        Budget: " . ($lead->budget ?? 'Not available') . "
        Status: {$lead->status}
        Priority: {$lead->priority}
        Last Note: {$lastNoteText}

        CRM Name:
        {$crmName}

        Company Name:
        {$companyName}
        ";

        $message = $gemini->generate($prompt);

        return back()->with('ai_followup_message', $message);
    }

    public function customerSummary(Customer $customer, GeminiService $gemini)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $customer->assigned_to !== auth()->id()) {
            abort(403);
        }

        $customer->load(['deals', 'tasks', 'tickets']);

        $latestNote = $customer->notes()->latest()->first();

        $prompt = "
You are a CRM assistant.

Create a clear customer summary for internal CRM users.
Keep it professional, simple, and under 150 words.
Mention customer status, deal situation, pending tasks, tickets, and next recommended action.

Customer Details:
Name: {$customer->name}
Company: " . ($customer->company_name ?? '-') . "
Phone: {$customer->phone}
Email: " . ($customer->email ?? '-') . "
Type: {$customer->type}
Status: {$customer->status}

Total Deals: " . $customer->deals->count() . "
Open Deals: " . $customer->deals->whereNotIn('stage', ['won', 'lost'])->count() . "
Won Deals: " . $customer->deals->where('stage', 'won')->count() . "
Pending Tasks: " . $customer->tasks->whereIn('status', ['pending', 'in_progress'])->count() . "
Open Tickets: " . $customer->tickets->whereIn('status', ['open', 'in_progress'])->count() . "
Latest Note: " . ($latestNote->note ?? 'No notes available') . "

Generate only the summary.
";

        $summary = $gemini->generate($prompt);

        return back()->with('ai_customer_summary', $summary);
    }

    public function ticketReply(Ticket $ticket, GeminiService $gemini)
    {
        if (auth()->user()->role === 'sales_executive') {
            abort(403);
        }

        if (auth()->user()->role === 'support_agent' && $ticket->assigned_to !== auth()->id()) {
            abort(403);
        }

        $ticket->load(['customer', 'replies.user']);

        $lastReply = $ticket->replies()->latest()->first();

        $prompt = "
    You are a professional customer support agent.

    Write a helpful support reply for this ticket.
    Tone: polite, clear, reassuring.
    Keep it under 120 words.
    Do not promise things that are not confirmed.
    Generate only the reply text.

    Important:
    Do not add any greeting signature.
    Do not write \"Your Name\", \"Support Agent\", \"Best regards\", \"Regards\", or any closing name.
    End naturally after the reply.

    Ticket Details:
    Subject: {$ticket->subject}
    Category: {$ticket->category}
    Priority: {$ticket->priority}
    Status: {$ticket->status}
    Description: {$ticket->description}

    Customer:
    Name: " . ($ticket->customer->name ?? '-') . "
    Company: " . ($ticket->customer->company_name ?? '-') . "

    Last Reply: " . ($lastReply->message ?? 'No previous replies') . "
    ";

        $reply = $gemini->generate($prompt);

        return back()->with('ai_ticket_reply', $reply);
    }

    public function sendLeadFollowUpEmail(Request $request, Lead $lead)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        if (auth()->user()->role === 'sales_executive' && $lead->assigned_to !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:3000',
        ]);

        if (!$lead->email) {
            return back()->with('error', 'Lead email address is missing.');
        }

        Mail::to($lead->email)->queue(new LeadFollowUpMail($lead, $request->message));

        return back()->with('success', 'Follow-up email queued successfully for ' . $lead->email);
    }
}