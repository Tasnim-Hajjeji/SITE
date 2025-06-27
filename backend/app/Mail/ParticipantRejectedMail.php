<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participantName;

    public function __construct($participantName)
    {
        $this->participantName = $participantName;
    }

    public function build()
    {
        return $this->subject('Rejection Notice')
                    ->view('emails.participant_rejected')
                    ->with(['name' => $this->participantName]);
    }
}
