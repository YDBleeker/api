<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\HtmlString;

class ReservationEmail extends Mailable
{
    public $userName;
    public $sportArticleName;
    public $reservationStartDate;
    public $reservationEndDate;
    public $count;

    /**
     * Create a new message instance.
     *
     * @param string $userName
     * @param string $sportArticleName
     * @param string $reservationDate
     * @param string $reservationEndDate
     */
    public function __construct($userName, $sportArticleName, $count, $reservationStartDate, $reservationEndDate)
    {
        $this->userName = $userName;
        $this->sportArticleName = $sportArticleName;
        $this->reservationStartDate = $reservationStartDate;
        $this->reservationEndDate = $reservationEndDate;
        $this->count = $count;
    }

    public function build()
    {
        return $this
            ->subject('Reservatie Aanvraag - sportinovatiecampus')
            ->view('emails.reservation')
            ->with([
                'userName' => $this->userName,
                'sportArticleName' => $this->sportArticleName,
                'reservationStartDate' => $this->reservationStartDate,
                'reservationEndDate' => $this->reservationEndDate,
                'count' => $this->count,
            ]);
    }

    /*public function attachments(): array
    {
        return [
            Attachment::fromStorage(storage_path('path/to/attachment.pdf'), 'attachment.pdf'),
        ];
    }*/
}
