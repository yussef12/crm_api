<?php

namespace App\Listeners;

use App\Events\JoinInvitationCreated;
use App\Mail\InvitationEmail;
use App\Models\EventLog;
use App\Models\JoinInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendJoinInvitationEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JoinInvitationCreated $event): void
    {
        $invitation = $event->joinInvitation;
        $name = $invitation->invited_name;
        $register_link = $invitation->app_url . '/' . $invitation->token;

        try {

            Mail::to($invitation->invited_email)->send(new InvitationEmail($register_link, $name));
            $ivt = JoinInvitation::findOrFail($invitation->id);
            $ivt->status = 'sent';
            $ivt->save();

            if ($ivt) {
                $user = Auth::user();
                EventLog::create([
                    'event_name' => 'invitation sent',
                    'triggered_by_name' => $user->name,
                    'triggered_by_id' => $user->id,
                    'triggered_by_role' =>$user->role->name,
                    'invited_employee_name' => $invitation->invited_name,
                ]);
            }


        } catch (\Exception $e) {
            Log::error('Error sending invitation: ' . $e->getMessage());

        }
    }
}
