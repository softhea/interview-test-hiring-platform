<?php

declare(strict_types=1);

namespace App\Mail;

use App\Interfaces\NotificationInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Config;

class NotifyCandidate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public NotificationInterface $notification)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                Config::get('app.mail_from_address'), 
                $this->notification->getSender()->getName()
            ),
            subject: $this->notification->getSubject(),
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mails.notify_candidate',
        );
    }
}
