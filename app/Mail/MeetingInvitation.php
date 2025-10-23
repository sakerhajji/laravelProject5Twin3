<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $roomName;

    public function __construct($roomName)
    {
        $this->roomName = $roomName;
    }

    public function build()
    {
        return $this->subject('Meeting Invitation')
                    ->view('emails.meeting-invitation');
    }
}
