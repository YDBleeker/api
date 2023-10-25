<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Services\Reservations\ReservationService;

class ReservationController extends Controller
{
    private $_reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->_reservationService = $reservationService;
    }

    public function all(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('M'));

        $reservations = $this->_reservationService->getReservations($year, $month);

        return response()->json($reservations);
    }

    public function create(Request $request)
    {
        $data = $request->only(['start_date', 'end_date', 'sport_article_id', 'count', 'name', 'email', 'course']);
        $result = $this->_reservationService->createReservation($data);

        if ($this->_reservationService->hasErrors()) {
            return response()->json($this->_reservationService->getErrors(), 400);
        }

        return response()->json($result);
    }

    public function detail($id)
    {
        $reservation = $this->_reservationService->getReservationsById($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }
}

