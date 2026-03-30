<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $status;

    public function __construct(Campaign $campaign, $status)
    {
        $this->campaign = $campaign;
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'active' 
            ? "Félicitations ! Votre session '{$this->campaign->name}' est approuvée"
            : "Mise à jour : Votre session '{$this->campaign->name}' a été refusée";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
