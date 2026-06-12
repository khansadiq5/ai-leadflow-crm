<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportCustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AiController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Users
    Route::resource('users', UserController::class);

    //All Resource
    Route::resource('leads', LeadController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('deals', DealController::class);
    Route::resource('tasks', TaskController::class);

    //Leads
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])
    ->name('leads.convert');

    //Deals
    Route::get('/deals-pipeline', [DealController::class, 'pipeline'])
    ->name('deals.pipeline');

    //Notes
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    //Task
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])
    ->name('tasks.complete');
    
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
    ->name('tasks.status.update');

    //Activity TimeLine
    Route::get('/leads/{lead}/activities', [ActivityController::class, 'lead'])
    ->name('leads.activities');

    Route::get('/customers/{customer}/activities', [ActivityController::class, 'customer'])
        ->name('customers.activities');

    Route::get('/deals/{deal}/activities', [ActivityController::class, 'deal'])
        ->name('deals.activities');
    
    Route::get('/tasks/{task}/activities', [ActivityController::class, 'task'])
        ->name('tasks.activities');
        
    //Notification
     Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::patch('/notifications/{notification}/mark-read', [NotificationController::class, 'markRead'])
        ->name('notifications.markRead');

    Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])
        ->name('notifications.open');

    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');

    //Ticket
    Route::resource('tickets', TicketController::class);

    Route::post('/tickets/{ticket}/replies', [TicketController::class, 'storeReply'])
        ->name('tickets.replies.store');

    Route::delete('/ticket-replies/{reply}', [TicketController::class, 'destroyReply'])
        ->name('tickets.replies.destroy');  
        
    //Customer Support 
    Route::get('/support/customers', [SupportCustomerController::class, 'index'])
        ->name('support.customers.index'); 

    Route::get('/support/customers/{customer}', [SupportCustomerController::class, 'show'])
        ->name('support.customers.show');    

    //Report
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    //PDF downloads
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
    ->name('reports.export.pdf');

    //CSV downloads
    Route::get('/reports/export/leads', [ReportController::class, 'exportLeads'])
    ->name('reports.export.leads');

    Route::get('/reports/export/deals', [ReportController::class, 'exportDeals'])
        ->name('reports.export.deals');

    Route::get('/reports/export/tasks', [ReportController::class, 'exportTasks'])
        ->name('reports.export.tasks');

    Route::get('/reports/export/tickets', [ReportController::class, 'exportTickets'])
        ->name('reports.export.tickets');

    //Setting
    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::put('/settings', [SettingController::class, 'update'])
        ->name('settings.update');
        
    //Ai Feature
    Route::post('/ai/leads/{lead}/follow-up', [AiController::class, 'leadFollowUp'])
        ->name('ai.leads.followup');

    Route::post('/ai/customers/{customer}/summary', [AiController::class, 'customerSummary'])
        ->name('ai.customers.summary');

    Route::post('/ai/tickets/{ticket}/reply', [AiController::class, 'ticketReply'])
        ->name('ai.tickets.reply');
    
    //Email send
    Route::post('/ai/leads/{lead}/send-follow-up-email', [AiController::class, 'sendLeadFollowUpEmail'])
        ->name('ai.leads.sendFollowupEmail');    

    //Logout    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
