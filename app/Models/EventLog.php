<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'triggered_by_name',
        'triggered_by_id',
        'invited_employee_name',
        'triggered_by_role',
    ];
}
