<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'assigned_to',
        'created_by',
        'name',
        'email',
        'phone',
        'company_name',
        'address',
        'city',
        'state',
        'pincode',
        'type',
        'status',
        'notes',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function customerNotes()
    {
        return $this->hasMany(Note::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'customer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}