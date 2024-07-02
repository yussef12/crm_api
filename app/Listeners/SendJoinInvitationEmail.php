<?php

namespace App\Listeners;

use App\Events\JoinInvitationCreated;
use App\Mail\InvitationEmail;
use App\Models\JoinInvitation;
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

        } catch (\Exception $e) {
            Log::error('Error sending invitation: ' . $e->getMessage());

        }
    }
}
