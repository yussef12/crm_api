<?php

namespace App\Http\Controllers;

use App\Models\EventLog;
use App\Models\JoinInvitation;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JoinInvitationController extends Controller
{

    public function getUserInvitations(Request $request)
    {

        $invited_email = $request->input('invited_email');
        $sortDirection = $request->input('sort');

        $userId = Auth::user()->id;
        $query = JoinInvitation::where('user_id', $userId);
        if ($sortDirection) {
            $query->orderBy($sortDirection);
        }
        if ($invited_email) {
            $query->where('invited_email', 'like', '%' . $invited_email . '%');
        }
        $invitations = $query->get();

        return response()->json(['invitations' => $invitations]);
    }


    public function cancel($id)
    {
        $invitation = JoinInvitation::findOrFail($id);
        if ($invitation->user_id == Auth::user()->id && $invitation->status != "validated") {
            $invitation->status = 'cancelled';
            $invitation->save();

            if ($invitation) {
                EventLog::create([
                    'name' => 'invitation cancelled',
                    'triggered_by_name' => Auth::user()->name,
                    'triggered_by_id' => Auth::user()->id,
                    'triggered_by_role' => Auth::user()->role->name,
                    'invited_employee_name' => $invitation->invited_name,
                ]);
            }
            return response()->json(['message' => 'invitation cancelled successfully.']);
        }

        return response()->json(['error' => 'Unauthorized.'], 403);
    }

    public function isInvitationLinkValid(Request $request)
    {
        $invitation = JoinInvitation::where('token', $request->token)->first();

        if ($invitation) {
            if ($invitation->status == "sent") {
                return response()->json(['is_valid' => true], 200);
            }
        }
        return response()->json(['is_valid' => false], 200);
    }
}
