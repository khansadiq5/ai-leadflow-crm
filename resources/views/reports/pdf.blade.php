<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CRM Performance Report</title>

    <style>
        @page {
            margin: 24px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.55;
            background: #ffffff;
        }

        .header {
            background: #0f172a;
            color: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 18px;
        }

        .brand-row {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-row td {
            vertical-align: top;
        }

        .logo-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #ffffff;
            color: #0f172a;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            line-height: 42px;
        }

        .eyebrow {
            font-size: 9px;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3px;
        }

        h1 {
            font-size: 23px;
            margin: 0;
            color: #ffffff;
            line-height: 1.2;
        }

        .period-card {
            text-align: right;
            font-size: 10px;
            color: #e2e8f0;
        }

        .period-card strong {
            color: #ffffff;
        }

        .generated {
            margin-top: 5px;
            color: #cbd5e1;
            font-size: 9px;
        }

        .section-title {
            margin: 18px 0 10px;
            padding: 9px 12px;
            border-left: 4px solid #0f172a;
            background: #f8fafc;
            border-radius: 8px;
        }

        .section-title h2 {
            font-size: 14px;
            margin: 0;
            color: #0f172a;
        }

        .section-title p {
            font-size: 9px;
            margin: 2px 0 0;
            color: #64748b;
        }

        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 8px;
        }

        .summary-grid td {
            width: 25%;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            vertical-align: top;
            background: #ffffff;
        }

        .summary-grid .dark {
            background: #0f172a;
            border-color: #0f172a;
            color: #ffffff;
        }

        .summary-grid .green {
            background: #ecfdf5;
            border-color: #bbf7d0;
        }

        .summary-grid .blue {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .summary-grid .red {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .card-label {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .dark .card-label {
            color: #cbd5e1;
        }

        .card-value {
            font-size: 19px;
            font-weight: bold;
            color: #0f172a;
            line-height: 1.2;
        }

        .dark .card-value {
            color: #ffffff;
        }

        .card-note {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }

        .dark .card-note {
            color: #cbd5e1;
        }

        .mini-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 7px;
            margin-bottom: 8px;
        }

        .mini-grid td {
            width: 25%;
            border-radius: 10px;
            padding: 10px;
            vertical-align: top;
            border: 1px solid #e2e8f0;
        }

        .mini-red {
            background: #fef2f2;
            border-color: #fecaca !important;
        }

        .mini-amber {
            background: #fffbeb;
            border-color: #fde68a !important;
        }

        .mini-blue {
            background: #eff6ff;
            border-color: #bfdbfe !important;
        }

        .mini-green {
            background: #ecfdf5;
            border-color: #bbf7d0 !important;
        }

        .mini-purple {
            background: #faf5ff;
            border-color: #e9d5ff !important;
        }

        .mini-label {
            font-size: 9px;
            color: #64748b;
        }

        .mini-value {
            margin-top: 3px;
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }

        .two-col {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .two-col td {
            width: 50%;
            vertical-align: top;
        }

        .box {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            background: #ffffff;
        }

        .box h3 {
            font-size: 12px;
            margin: 0 0 8px;
            color: #0f172a;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            table-layout: fixed;
        }

        table.data th {
            background: #f1f5f9;
            color: #334155;
            font-size: 9.5px;
            text-align: left;
            padding: 8px 7px;
            border: 1px solid #e2e8f0;
            font-weight: bold;
        }

        table.data td {
            padding: 8px 7px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            color: #334155;
            word-wrap: break-word;
        }

        table.data tr:nth-child(even) td {
            background: #f8fafc;
        }

        .amount {
            font-weight: bold;
            color: #047857;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-dark {
            background: #0f172a;
            color: #ffffff;
        }

        .badge-green {
            background: #dcfce7;
            color: #047857;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .muted {
            color: #64748b;
        }

        .insight-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .insight-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .footer {
            margin-top: 26px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #64748b;
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @php
        $ticketPriorityCounts = $ticketPriorityCounts ?? [
            'low' => 0,
            'medium' => 0,
            'high' => 0,
            'urgent' => $urgentTickets ?? 0,
        ];

        $leadPriorityCounts = $leadPriorityCounts ?? [
            'hot' => $hotLeads ?? 0,
            'warm' => $warmLeads ?? 0,
            'cold' => $coldLeads ?? 0,
        ];

        $cancelledTasks = $cancelledTasks ?? ($taskStatusCounts['cancelled'] ?? 0);
    @endphp

    {{-- HEADER --}}
    <div class="header">
        <table class="brand-row">
            <tr>
                <td style="width: 54px;">
                    <div class="logo-box">L</div>
                </td>

                <td>
                    <div class="eyebrow">{{ setting('company_name', 'LeadFlow CRM') }}</div>
                    <h1>{{ setting('pdf_report_title', 'CRM Performance Report') }}</h1>
                    <div class="generated">
                        Generated on {{ now()->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                    </div>
                </td>

                <td class="period-card" style="width: 210px;">
                    <div>Report Period</div>
                    <strong>{{ $fromDate->format('d M Y') }}</strong>
                    <span> to </span>
                    <strong>{{ $toDate->format('d M Y') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    {{-- EXECUTIVE SUMMARY --}}
    <div class="section-title">
        <h2>Executive Summary</h2>
        <p>High-level CRM performance snapshot for the selected period.</p>
    </div>

    <table class="summary-grid">
        <tr>
            <td class="dark">
                <div class="card-label">Total Leads</div>
                <div class="card-value">{{ $totalLeads }}</div>
                <div class="card-note">{{ $convertedLeads }} converted leads</div>
            </td>

            <td class="green">
                <div class="card-label">Won Revenue</div>
                <div class="card-value">Rs. {{ number_format($wonRevenue, 0) }}</div>
                <div class="card-note">{{ $wonDeals }} won deals</div>
            </td>

            <td class="blue">
                <div class="card-label">Completed Tasks</div>
                <div class="card-value">{{ $completedTasks }}</div>
                <div class="card-note">{{ $overdueTasks }} overdue tasks</div>
            </td>

            <td class="red">
                <div class="card-label">Support Tickets</div>
                <div class="card-value">{{ $totalTickets }}</div>
                <div class="card-note">{{ $urgentTickets }} urgent tickets</div>
            </td>
        </tr>
    </table>

    <table class="mini-grid">
        <tr>
            <td class="mini-red">
                <div class="mini-label">Hot Leads</div>
                <div class="mini-value">{{ $hotLeads }}</div>
            </td>

            <td class="mini-blue">
                <div class="mini-label">Open Deals</div>
                <div class="mini-value">{{ $openDeals }}</div>
            </td>

            <td class="mini-amber">
                <div class="mini-label">Pending Tasks</div>
                <div class="mini-value">{{ $pendingTasks }}</div>
            </td>

            <td class="mini-purple">
                <div class="mini-label">Pipeline Revenue</div>
                <div class="mini-value">Rs. {{ number_format($pipelineRevenue, 0) }}</div>
            </td>
        </tr>
    </table>

    {{-- LEAD + CUSTOMER --}}
    <div class="section-title">
        <h2>Lead & Customer Summary</h2>
        <p>Lead quality, conversion and customer status overview.</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Total Leads</th>
                <th>Hot</th>
                <th>Warm</th>
                <th>Cold</th>
                <th>Converted</th>
                <th>Total Customers</th>
                <th>Active Customers</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalLeads }}</td>
                <td><span class="badge badge-red">{{ $hotLeads }}</span></td>
                <td><span class="badge badge-amber">{{ $warmLeads }}</span></td>
                <td><span class="badge badge-dark">{{ $coldLeads }}</span></td>
                <td><span class="badge badge-green">{{ $convertedLeads }}</span></td>
                <td>{{ $totalCustomers }}</td>
                <td><span class="badge badge-green">{{ $activeCustomers }}</span></td>
            </tr>
        </tbody>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <div class="box">
                    <h3>Lead Status Breakdown</h3>
                    <table class="data">
                        <tbody>
                            @foreach($leadStatusCounts as $status => $count)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $status)) }}</td>
                                    <td><strong>{{ $count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </td>

            <td>
                <div class="box">
                    <h3>Customer Status</h3>
                    <table class="data">
                        <tbody>
                            <tr>
                                <td>Active</td>
                                <td><span class="badge badge-green">{{ $activeCustomers }}</span></td>
                            </tr>
                            <tr>
                                <td>Inactive</td>
                                <td><span class="badge badge-red">{{ $inactiveCustomers }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- DEALS --}}
    <div class="section-title">
        <h2>Deal & Revenue Summary</h2>
        <p>Pipeline performance, deal stage movement and revenue analysis.</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Total Deals</th>
                <th>Open Deals</th>
                <th>Won Deals</th>
                <th>Lost Deals</th>
                <th>Won Revenue</th>
                <th>Pipeline Revenue</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalDeals }}</td>
                <td><span class="badge badge-blue">{{ $openDeals }}</span></td>
                <td><span class="badge badge-green">{{ $wonDeals }}</span></td>
                <td><span class="badge badge-red">{{ $lostDeals }}</span></td>
                <td class="amount">Rs. {{ number_format($wonRevenue, 2) }}</td>
                <td class="amount">Rs. {{ number_format($pipelineRevenue, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Deal Stage</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dealStageCounts as $stage => $count)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $stage)) }}</td>
                    <td><strong>{{ $count }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TASKS --}}
    <div class="section-title">
        <h2>Task Performance</h2>
        <p>Follow-up workload, completion status and overdue activity.</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Total Tasks</th>
                <th>Pending</th>
                <th>In Progress</th>
                <th>Completed</th>
                <th>Overdue</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalTasks }}</td>
                <td><span class="badge badge-amber">{{ $pendingTasks }}</span></td>
                <td><span class="badge badge-blue">{{ $inProgressTasks }}</span></td>
                <td><span class="badge badge-green">{{ $completedTasks }}</span></td>
                <td><span class="badge badge-red">{{ $overdueTasks }}</span></td>
            </tr>
        </tbody>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Task Status</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taskStatusCounts as $status => $count)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $status)) }}</td>
                    <td><strong>{{ $count }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SUPPORT --}}
    <div class="section-title">
        <h2>Support Ticket Summary</h2>
        <p>Support workload, resolution status and urgent case volume.</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Total Tickets</th>
                <th>Open</th>
                <th>In Progress</th>
                <th>Resolved</th>
                <th>Closed</th>
                <th>Urgent</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalTickets }}</td>
                <td><span class="badge badge-amber">{{ $openTickets }}</span></td>
                <td><span class="badge badge-blue">{{ $inProgressTickets }}</span></td>
                <td><span class="badge badge-green">{{ $resolvedTickets }}</span></td>
                <td><span class="badge badge-dark">{{ $closedTickets }}</span></td>
                <td><span class="badge badge-red">{{ $urgentTickets }}</span></td>
            </tr>
        </tbody>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <div class="box">
                    <h3>Ticket Status Breakdown</h3>
                    <table class="data">
                        <tbody>
                            @foreach($ticketStatusCounts as $status => $count)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $status)) }}</td>
                                    <td><strong>{{ $count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </td>

            <td>
                <div class="box">
                    <h3>Ticket Priority Breakdown</h3>
                    <table class="data">
                        <tbody>
                            @foreach($ticketPriorityCounts as $priority => $count)
                                <tr>
                                    <td>{{ ucfirst($priority) }}</td>
                                    <td><strong>{{ $count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- TEAM PERFORMANCE --}}
    <div class="section-title page-break">
        <h2>Team Performance</h2>
        <p>Sales and support team productivity for the selected report period.</p>
    </div>

    <h3>Sales Team Performance</h3>

    <table class="data">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Assigned Leads</th>
                <th>Assigned Tasks</th>
                <th>Completed Tasks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salesUsers as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ ucwords(str_replace('_', ' ', $user->role)) }}</td>
                    <td>{{ $user->assigned_leads_count }}</td>
                    <td>{{ $user->assigned_tasks_count }}</td>
                    <td><span class="badge badge-green">{{ $user->completed_tasks_count }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No sales users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Support Team Performance</h3>

    <table class="data">
        <thead>
            <tr>
                <th>Agent</th>
                <th>Assigned Tickets</th>
                <th>Resolved Tickets</th>
                <th>Resolution Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supportUsers as $user)
                @php
                    $rate = $user->assigned_tickets_count > 0
                        ? round(($user->resolved_tickets_count / $user->assigned_tickets_count) * 100)
                        : 0;
                @endphp

                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->assigned_tickets_count }}</td>
                    <td><span class="badge badge-green">{{ $user->resolved_tickets_count }}</span></td>
                    <td><span class="badge badge-blue">{{ $rate }}%</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No support agents found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- IMPORTANT RECORDS --}}
    <div class="section-title">
        <h2>Important Records</h2>
        <p>Recent wins, urgent support cases and overdue follow-ups.</p>
    </div>

    <h3>Recent Won Deals</h3>

    <table class="data">
        <thead>
            <tr>
                <th>Deal</th>
                <th>Customer</th>
                <th>Assigned User</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentWonDeals as $deal)
                <tr>
                    <td><strong>{{ $deal->title }}</strong></td>
                    <td>{{ $deal->customer->name ?? '-' }}</td>
                    <td>{{ $deal->assignedUser->name ?? '-' }}</td>
                    <td class="amount">Rs. {{ number_format($deal->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No won deals found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Urgent Tickets</h3>

    <table class="data">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Customer</th>
                <th>Assigned Agent</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUrgentTickets as $ticket)
                <tr>
                    <td><strong>{{ $ticket->subject }}</strong></td>
                    <td>{{ $ticket->customer->name ?? '-' }}</td>
                    <td>{{ $ticket->assignedUser->name ?? '-' }}</td>
                    <td><span class="badge badge-red">{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No urgent tickets found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Overdue Tasks</h3>

    <table class="data">
        <thead>
            <tr>
                <th>Task</th>
                <th>Assigned User</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOverdueTasks as $task)
                <tr>
                    <td><strong>{{ $task->title }}</strong></td>
                    <td>{{ $task->assignedUser->name ?? '-' }}</td>
                    <td><span class="badge badge-red">{{ ucwords(str_replace('_', ' ', $task->status)) }}</span></td>
                    <td>{{ $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No overdue tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="insight-box">
        <div class="insight-title">Report Note</div>
        <div class="muted">
            This report was generated automatically by LeadFlow CRM using the selected date range.
            Values are based on records created within the report period.
        </div>
    </div>

    <div class="footer">
        {{ setting('pdf_footer_text', 'This report was generated automatically by LeadFlow CRM.') }}
    </div>

</body>
</html>