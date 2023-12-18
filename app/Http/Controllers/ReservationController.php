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
        $year = $request->input('year');
        $month = $request->input('month');
        $week = $request->input('week');
        $approved = $request->input('approved');

        $reservations = $this->_reservationService->getReservations($year, $month, $week, $approved);

        return response()->json($reservations);
    }

    public function history(Request $request)
    {
        $reservations = $this->_reservationService->getReservationshistory();

        return response()->json($reservations);
    }

    public function create(Request $request)
    {
        $data = $request->only(['start_date', 'end_date', 'sport_article_id', 'count', 'phone', 'name', 'email', 'course']);
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

    public function approve($id)
    {
        $message = $this->_reservationService->approveReservation($id);

        return response()->json($message);
    }

    public function delete($id)
    {
        $message = $this->_reservationService->deleteReservation($id);

        return response()->json($message);
    }

    public function cancel(Request $request, $id)
    {
        $cancelMessage = $request->input('message', "");
        $message = $this->_reservationService->cancelReservation($id, $cancelMessage);

        return response()->json($message);
    }

    public function lent($id)
    {
        $message = $this->_reservationService->lent($id);

        return response()->json($message);
    }
}

