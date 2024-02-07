<?php

namespace App\Mail;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $message;
    private Candidate $candidate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $message, Candidate $candidate, ?string $subject = 'MZT Contact')
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->candidate = $candidate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.ContactEmail')
            ->with(
                'data',
                [
                'message' => $this->message,
                'subject' => $this->subject,
                'candidate' => $this->candidate,
                ]
            )
            ->subject($this->subject);
    }
}
