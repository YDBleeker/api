<?php

namespace App\Modules\Services\Reservations;

use App\Modules\Services\Service;
use App\Models\Reservation;
use App\Models\SportArticle;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationEmail;
use App\Mail\ReservationAdminEmail;
use App\Mail\ReservationApproveEmail;
use App\Mail\ReservationCancelEmail;
use DateTime;

class ReservationService extends Service
{
    protected $_rules = [
        'sport_article_id' => 'required|integer',
        'count' => 'required|integer',
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:255',
        'course' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ];

    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function getReservations($year = null, $month = null, $week = null, $approved = null)
    {
        $query = $this->_model->with("sportarticle");

        if ($year !== null) $query->whereYear('start_date', $year);
        if ($month !== null) $query->whereMonth('start_date', $month);
        if ($week !== null) $query->where('start_date', $week);


        if ($approved !== null){
            $query->where('confirmed', $approved);
            $query->where('history', false);

        }
        else {
            $query->where('confirmed', false);
            $query->where('history', false);
        }

        return $query->get();
    }


    public function createReservation($data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        //check if the sport article exists
        $sportArticle = SportArticle::find($data['sport_article_id']);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        //check if the sport article count is smaller than the reservation count
        if ($sportArticle->count <= $data['count']) {
            $this->_errors->add('count', 'Not enough sport articles available');
            return;
        }


        //check if start date is before end date
        if ($data['start_date'] > $data['end_date']) {
            $this->_errors->add('start_date', 'Start date must be before end date');
            return;
        }

        //check if start date is in the past
        if ($data['start_date'] < now()->format('Y-m-d')) {
            $this->_errors->add('start_date', 'Start date must be in the future');
            return;
        }

        //check how long the reservation is
        $start_date = new DateTime($data['start_date']);
        $end_date = new DateTime($data['end_date']);
        $interval = $start_date->diff($end_date);
        $days = $interval->format('%a');
        if ($days > $sportArticle->max_reservation_days) {
            $this->_errors->add('max_reservation_days', 'Reservation can not be longer than ' . $sportArticle->max_reservation_days . ' days');
            return;
        }

        //get reservations in the same period
        $reservations = $this->_model
        ->where('sport_article_id', $data['sport_article_id'])
        ->where('history', false)
        ->where(function ($query) use ($data) {
            $query->where(function ($subquery) use ($data) {
                $subquery->where('start_date', '<=', $data['end_date'])
                    ->where('end_date', '>=', $data['start_date']);
            });
        })
        ->get();

        //check if the reservation limit is exceeded
        $dayCounts = [];
        foreach ($reservations as $reservation) {
            $startDate = new DateTime($reservation->start_date);
            $endDate = new DateTime($reservation->end_date);
            $articleCount = $reservation->count;

            while ($startDate <= $endDate) {
                $day = $startDate->format('Y-m-d');
                if (!isset($dayCounts[$day])) { //check if the key exist
                    $dayCounts[$day] = 0;
                }
                $dayCounts[$day]+= $articleCount;
                $startDate->modify('+1 day');
            }
        }

        $availableSportArticleCount = $sportArticle->count;
        foreach ($dayCounts as $day => $count) {
            if (($count + $data['count']) > $availableSportArticleCount) {
                $this->_errors->add('count', 'Reservation limit exceeded for one or more days');
                return;
            }
        }

        $model = $this->_model->create($data);
        mail::to($data['email'])->send(new ReservationEmail($data['name'], $sportArticle['name'], $data['count'], $data['start_date'], $data['end_date']));
        mail::to(env('MAIL_TO'))->send(new ReservationAdminEmail($sportArticle['name'], $data['count'], $data['start_date'], $data['end_date']));
        return $model;
    }

    public function checkSportArticleAvailability($sportArticleId, $start_date, $end_date, $availableSportArticleCount  )
    {
        $reservations = $this->_model
            ->where('sport_article_id', $sportArticleId)
            ->where(function ($query) use ($start_date, $end_date) {
                $query->where('start_date', '<=', $end_date)
                    ->where('end_date', '>=', $start_date)
                    ->where('history', false);
            })
            ->get();

        // Check if the reservation limit is exceeded
        $dayCounts = [];
        foreach ($reservations as $reservation) {
            $startDate = new DateTime($reservation->start_date);
            $endDate = new DateTime($reservation->end_date);
            $articleCount = $reservation->count;

            while ($startDate <= $endDate) {
                $day = $startDate->format('Y-m-d');
                $dayCounts[$day] = isset($dayCounts[$day]) ? $dayCounts[$day] : 0;
                $dayCounts[$day] += $articleCount;
                $startDate->modify('+1 day');
            }
        }

        // Check if the sport article is available for the specified period
        $availableSportArticles = $availableSportArticleCount;
        $countlist = [];
        foreach ($dayCounts as $day => $count) {

            if ($count >= $availableSportArticleCount) {
                return 0;
            }

            if ($count < $availableSportArticles) {
                $countlist[] = $availableSportArticles - $count;
            }

        }

        // Check if there's anything in the countlist array
        if (!empty($countlist)) {
            return min($countlist);
        }

        // If the countlist is empty, return the total count
        return $maxAvailableSportArticles;
    }


    public function getReservationsById($id)
    {
        $reservation = $this->_model->find($id);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        return $reservation;
    }

    public function approveReservation($id)
    {
        $reservation = $this->_model->find($id);
        //dd($reservation);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        $sportArticle = SportArticle::find($reservation['sport_article_id']);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }


        $reservation->update(['confirmed' => true]);
        //dd($reservation);
        mail::to($reservation['email'])->send(new ReservationApproveEmail($sportArticle['name'], $reservation['count'], $reservation['start_date'], $reservation['end_date'], $reservation['name']));

        return 'Reservation approved';
    }

    public function lent($id)
    {
        $reservation = $this->_model->find($id);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        $sportArticle = SportArticle::find($reservation['sport_article_id']);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        $reservation->update(['lent' => true]);
        return 'Reservation lent';
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

    public function cancelReservation($id, $cancelMessage)
    {
        $reservation = $this->_model->find($id);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        $sportArticle = SportArticle::find($reservation['sport_article_id']);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        mail::to($reservation['email'])->send(new ReservationCancelEmail($sportArticle['name'], $reservation['count'], $reservation['start_date'], $reservation['end_date'], $reservation['name'], $cancelMessage));
        $reservation->delete();

        return 'Reservation canceled';
    }

    public function reduceReservation($id)
    {
        $reservation = $this->_model->find($id);
        if (!$reservation) {
            $this->_errors->add('reservation', 'Reservation not found');
            return;
        }

        $reservation->update(['history' => true]);
        return 'Reservation reduced';
    }

    public function getReservationshistory($perPage = 10, $page = 1)
    {
        $reservations = $this->_model->with("sportarticle")
                                     ->where('history', true)
                                     ->orderBy('end_date', 'desc')
                                     ->paginate($perPage, ['*'], 'page', $page);

        return $reservations;
    }



}

