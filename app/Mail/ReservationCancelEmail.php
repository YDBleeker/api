<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCancelEmail extends Mailable
{

    public $sportArticleName;
    public $reservationStartDate;
    public $reservationEndDate;
    public $count;
    public $message;

    /**
     * Create a new message instance.
     *
     * @param string $sportArticleName
     * @param string $reservationDate
     * @param string $reservationEndDate
     * @param int $count
     * @param string $message
     */
     */
    public function __construct($sportArticleName, $count, $reservationStartDate, $reservationEndDate, $message)
    {
        $this->sportArticleName = $sportArticleName;
        $this->reservationStartDate = $reservationStartDate;
        $this->reservationEndDate = $reservationEndDate;
        $this->count = $count;
        $this->message = $message;
    }

    public function build()
    {
        return $this
            ->subject('Reservatie Aanvraag Geannuleerd - sportinovatiecampus')
            ->view('emails.reservationAdmin')
            ->with([
                'sportArticleName' => $this->sportArticleName,
                'reservationStartDate' => $this->reservationStartDate,
                'reservationEndDate' => $this->reservationEndDate,
                'count' => $this->count,
                'message' => $this->message,
            ]);
    }
}
