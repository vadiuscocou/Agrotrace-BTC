<?php

namespace App\Mail;

use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestmentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $investment;

    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre investissement AgroTrace-BTC',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.investments.confirmed',
            with: [
                'projectTitle' => $this->investment->project->title,
                'amount' => $this->investment->amount_fcfa,
                'hash' => $this->investment->payment_hash,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
