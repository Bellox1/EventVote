<?php

namespace App\Mail;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $status;

    public function __construct(Candidate $candidate, $status)
    {
        $this->candidate = $candidate;
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'accepted' 
            ? "Félicitations ! Votre candidature pour '{$this->candidate->campaign->name}' est acceptée"
            : "Mise à jour : Votre candidature pour '{$this->candidate->campaign->name}' a été refusée";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.candidate-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
