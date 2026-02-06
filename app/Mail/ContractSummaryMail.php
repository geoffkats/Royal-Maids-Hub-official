<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\Maid;
use App\Models\MaidContract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MaidContract $contract,
        public Client $client,
        public Maid $maid
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Royal Maids Hub Contract Summary')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contract-summary',
            with: [
                'contract' => $this->contract,
                'client' => $this->client,
                'maid' => $this->maid,
            ]
        );
    }
}
