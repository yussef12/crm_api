<?php

namespace App\Http\Controllers;

use App\Models\JoinInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JoinInvitationController extends Controller
{

    public function getUserInvitations()
    {
        $userId = Auth::user()->id;
        $invitations = JoinInvitation::where('user_id', $userId)->get();

        return response()->json(['invitations' => $invitations]);
    }


    public function cancel($id)
    {
        $invitation = JoinInvitation::findOrFail($id);
        if ($invitation->user_id == Auth::user()->id && $invitation->status !="validated") {
            $invitation->status = 'cancelled';
            $invitation->save();
            return response()->json(['message' => 'invitation cancelled successfully.']);
        }

        return response()->json(['error' => 'Unauthorized.'], 403);
    }
}
