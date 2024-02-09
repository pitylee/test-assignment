<?php

namespace App\Observers;

use App\Mail\ContactEmail;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     *
     * @param \App\Models\Message $message
     * @return void
     */
    public function created(Message $message)
    {
        $emails = [
            $message->candidate()->first()->email,
        ];

        // Could be event here
        Mail::to($emails)->send(
            new ContactEmail(
                $message->message,
                $message->candidate()->first(),
                $message->subject
            )
        );
    }

    /**
     * Handle the Message "updated" event.
     *
     * @param \App\Models\Message $message
     * @return void
     */
    public function updated(Message $message)
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     *
     * @param \App\Models\Message $message
     * @return void
     */
    public function deleted(Message $message)
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     *
     * @param \App\Models\Message $message
     * @return void
     */
    public function restored(Message $message)
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     *
     * @param \App\Models\Message $message
     * @return void
     */
    public function forceDeleted(Message $message)
    {
        //
    }
}
