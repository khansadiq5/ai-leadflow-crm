<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'assigned_to',
        'converted_customer_id',
        'name',
        'email',
        'phone',
        'company_name',
        'source',
        'interested_service',
        'budget',
        'status',
        'priority',
        'follow_up_date',
        'description',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function convertedCustomer()
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    public function tasks()
    {
    return $this->hasMany(Task::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}