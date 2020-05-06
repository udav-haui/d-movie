<?php

namespace App\Http\Controllers\Adminhtml;

use App\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Ticket;
use Illuminate\Http\Request;

class BookingController extends \App\Http\Controllers\Controller
{
    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * BookingController constructor.
     *
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Booking::class);
        try {
            $bookings = $this->bookingRepository->all(['combo', 'user'])->sortByDesc('created_at');

            if (request()->ajax()) {
                $dt = datatables()->of($bookings);

                $dt->editColumn('created_at', function (Booking $booking) {
                    return $booking->getFormattedDate();
                });

                $dt->editColumn('status', function (Booking $booking) {
                    $html = "<span class=\"";
                    if ($booking->getStatus() == Booking::SUCCESS) {
                        $html .= "text-success";
                    }
                    if ($booking->getStatus() == Booking::CANCEL_BY_CUSTOMER) {
                        $html .= "text-warning";
                    }
                    if ($booking->getStatus() == Booking::WAITING_FOR_PAYMENT) {
                        $html .= "text-danger";
                    }
                    return $html . "\">" . $booking->getStatusLabel() . "</span>";
                });

                $dt->addColumn('combo_name', function (Booking $booking) {
                    if ($combo = $booking->getCombo()) {
                        return $combo->getName();
                    }
                    return '';
                });

                $authUser = auth()->user();

                if ($authUser->can('canEditDelete', Booking::class)) {
                    $htmlRaw = "";
                    $dt->addColumn('task', function (Booking $booking) use ($authUser, $htmlRaw) {
                        if ($authUser->can('update', Booking::class)) {
                            $htmlRaw .= "<a href=\"" . route('bookings.show', ['booking' => $booking->getId()]) . "\"
                                   type=\"button\" class=\"";
//                        if ($authUser->cant('delete', Booking::class)) {
//                            $htmlRaw .= "col-md-12 ";
//                        } else {
//                            $htmlRaw .= 'col-md-6 ';
//                        }
                            $htmlRaw .= "col-md-12 ";

                            $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                            $htmlRaw .= "title=\"" . __('View Tickets') . "\">";
                            $htmlRaw .= "<i class=\"fa fa-ticket\"></i></a>";
                        }
                        return $htmlRaw;
                    });
                } else {
                    $dt->addColumn('task', '');
                }

                return $dt->rawColumns(
                    ['created_at', 'status', 'task', 'combo_name']
                )->make();
            }

            return view('admin.booking.index');
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return back()->with('error', __('The function is developing'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Booking  $booking
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        $this->authorize('printTicket', Booking::class);

        if (request()->ajax()) {
            $tickets = $booking->tickets()->with(['seat', 'time']);
            $dt = datatables()->of($tickets);
            $dt->editColumn('created_at', function (Ticket $ticket) use ($booking) {
                return $booking->getFormattedDate($ticket->created_at);
            });
            $dt->addColumn('seat', function (Ticket $ticket) {
                return $ticket->getSeat()->getRow() . $ticket->getSeat()->getNumber();
            });
            $dt->addColumn('start_time', function (Ticket $ticket) {
                return $ticket->getTime()->getStartTime();
            });
            $authUser = auth()->user();

            if ($authUser->can('printTicket', Booking::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (Ticket $ticket) use ($authUser, $htmlRaw) {
                    $htmlRaw .= "<a href=\"" . route('bookings.printTicket', ['ticket' => $ticket]) . "\"
                                   type=\"button\" class=\"";
                    $htmlRaw .= "col-md-12 ";

                    $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                    $htmlRaw .= "title=\"" . __('Print this ticket') . "\">";
                    $htmlRaw .= "<i class=\"fa fa-print\"></i></a>";
                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('task', '');
            }

            return $dt->rawColumns(
                ['created_at', 'status', 'task', 'seat', 'start_time']
            )->make();
        }

        return view('admin.booking.ticket.index', compact('booking'));
    }

    public function printTicket()
    {
        $this->authorize('printTicket', \App\Booking::class);
        try {
            return view();
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
