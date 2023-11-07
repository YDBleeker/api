<?php

namespace App\Modules\Services\Reservations;

use App\Modules\Services\Service;
use App\Models\Reservation;
use App\Models\SportArticle;

class ReservationService extends Service
{
    protected $_rules = [
        'sport_article_id' => 'required|integer',
        'count' => 'required|integer',
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'course' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ];

    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function getReservations($year, $month, $week)
    {
        $reservations = $this->_model
        ->with("sportarticle")
        //->whereYear('start_date', $year)
        //->whereMonth('start_date', $month)
        //->where('start_date', $week)
        ->get();

        return $reservations;
    }

    public function createReservation($data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        /*$sportArticle = SportArticle::find($data['sport_article_id']);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        $reservations = $this->_model
        ->where('sport_article_id', $data['sport_article_id'])
        ->where(function ($query) use ($data) {
            $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                  ->whereBetween('end_date', [$data['start_date'], $data['end_date']]);
        })
        ->get();

        dd($reservations)*/

        //day + 1 loop over the $reservations parameter count every loop the items for that day in de reservations
        //if the count is higher than the sport article count return error
        //if not create reservation




        $model = $this->_model->create($data);
        return $model;
    }

    public function getReservationsById($id)
    {
        //add error handling if reservation not found
        $reservation = $this->_model->find($id);
        return $reservation;

    }

    public function approveReservation($id)
    {
        $reservation = $this->_model->find($id);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        $reservation->status = true;

        return 'Reservation approved';
    }

    public function deleteReservation($id)
    {
        $reservation = $this->_model->find($id);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        $reservation->delete();

        return 'Reservation deleted';
    }




}

