<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Models\Data_kendaraan;
use Illuminate\Support\Facades\Log;

class AdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder Pajak Kendaraan',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $item = $this->item;
        $today = Carbon::today();

        Log::info("ini AdminMail.php, Data : " . json_encode($item));
        //dd($item);
        return new Content(
            view: 'emails.Emailtemplate1',
            with: [
                'today' => $today->format('Y-m-d'),
                'reminderGroups' => $item,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
