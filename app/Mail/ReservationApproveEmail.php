<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationApproveEmail extends Mailable
{

    public $sportArticleName;
    public $reservationStartDate;
    public $reservationEndDate;
    public $count;

    /**
     * Create a new message instance.
     *
     * @param string $sportArticleName
     * @param string $reservationDate
     * @param string $reservationEndDate
     * @param int $count
     */
    public function __construct($sportArticleName, $count, $reservationStartDate, $reservationEndDate, $username)
    {
        $this->sportArticleName = $sportArticleName;
        $this->reservationStartDate = $reservationStartDate;
        $this->reservationEndDate = $reservationEndDate;
        $this->count = $count;
        $this->username = $username;
    }

    public function build()
    {
        return $this
            ->subject('Reservatie Aanvraag Goedgekeurd - sportinovatiecampus')
            ->view('emails.reservationApprove')
            ->with([
                'sportArticleName' => $this->sportArticleName,
                'reservationStartDate' => $this->reservationStartDate,
                'reservationEndDate' => $this->reservationEndDate,
                'count' => $this->count,
                'userName' => $this->username,
            ]);
    }
}
