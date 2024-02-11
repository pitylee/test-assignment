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

    /**
     * @var string
     */
    private string $message;
    /**
     * @var Candidate
     */
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
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return Candidate
     */
    public function getCandidate(): Candidate
    {
        return $this->candidate;
    }

    /**
     * @param Candidate $candidate
     * @return void
     */
    public function setCandidate(Candidate $candidate): void
    {
        $this->candidate = $candidate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
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
