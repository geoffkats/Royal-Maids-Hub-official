<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\ClientEvaluationLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientEvaluationLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public ClientEvaluationLink $link,
        public string $url
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Royal Maids Hub Client Evaluation')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-evaluation-link',
            with: [
                'booking' => $this->booking,
                'link' => $this->link,
                'url' => $this->url,
            ]
        );
    }
}
