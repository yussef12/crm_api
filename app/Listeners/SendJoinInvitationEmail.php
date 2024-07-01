<?php

namespace App\Listeners;

use App\Events\JoinInvitationCreated;
use App\Mail\InvitationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        $register_link =$invitation->app_url.'/'.$invitation->token;
        Mail::to($invitation->invited_email)->send(new InvitationEmail($register_link));
    }
}
