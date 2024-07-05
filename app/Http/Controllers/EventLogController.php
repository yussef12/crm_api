<?php

namespace App\Http\Controllers;

use App\Models\EventLog;
use Illuminate\Http\Request;

class EventLogController extends Controller
{
    public function index()
    {
        $eventLogs = EventLog::all();
        return response()->json(['logs' => $eventLogs]);
    }
}
