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
        //dd($week);
        $reservations = $this->_model
        ->with("sportarticle")
        ->whereYear('start_date', $year)
        ->whereMonth('start_date', $month)
        ->where('start_date', $week)
        ->get();

        return $reservations;
    }

    public function createReservation($data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        //TODO afwerken
        //check on the start_date if there are enouf sport articles
        $reservations = $this->_model
        ->where('start_date', $data['start_date'])
        ->where('sport_article_id', $data['sport_article_id'])
        ->get();

        /*
        $start_date = new DateTime($data['start_date']);
        $end_date = new DateTime($data['end_date']);
        $interval = $start_date->diff($end_date);

        $numDays = $interval->days;
        */
        checkAvailability($data['start_date'], $numDays)
        {

        }
        
        $sportArticle = SportArticle::find($data['sport_article_id']);

        $count = $reservations->sum('count');

        if ($count + $data['count'] > $sportArticle->count) {
            $this->_errors->add('count', 'Not enouf sport articles');
            return;
        }
        //

        $sportArticle = SportArticle::find($data['sport_article_id']);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        $model = $this->_model->create($data);
        return $model;
    }

    public function getReservationsById($id)
    {
        //add error handling if reservation not found
        $reservation = $this->_model->find($id);
        return $reservation;

    }




}

