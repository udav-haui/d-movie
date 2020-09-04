<?php

namespace App\Http\Controllers\Frontend;

use App\Booking;
use App\Config;
use App\Events\NewBookingEvent;
use App\Events\NewJoinerEvent;
use App\Events\SeatSelectedStatus;
use App\Exceptions\UnknownException;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\ComboRepositoryInterface;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Repositories\Interfaces\StoreConfigRepositoryInterface;
use App\Repositories\Interfaces\TimeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Ticket;
use App\Time;

/**
 * Class BookingController
 *
 * @package App\Http\Controllers\Frontend
 */
class BookingController extends Controller
{
    /**
     * @var StoreConfigRepositoryInterface
     */
    protected $configRepository;

    /**
     * @var FilmRepositoryInterface
     */
    private $filmRepository;

    /**
     * @var TimeRepositoryInterface
     */
    private $timeRepository;

    /**
     * @var ComboRepositoryInterface
     */
    private $comboRepository;

    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * @var SeatRepositoryInterface
     */
    private $seatRepository;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * BookingController constructor.
     *
     * @param FilmRepositoryInterface $filmRepository
     * @param SeatRepositoryInterface $seatRepository
     * @param TimeRepositoryInterface $timeRepository
     * @param UserRepositoryInterface $userRepository
     * @param ComboRepositoryInterface $comboRepository
     * @param BookingRepositoryInterface $bookingRepository
     * @param StoreConfigRepositoryInterface $configRepository
     */
    public function __construct(
        FilmRepositoryInterface $filmRepository,
        SeatRepositoryInterface $seatRepository,
        TimeRepositoryInterface $timeRepository,
        UserRepositoryInterface $userRepository,
        ComboRepositoryInterface $comboRepository,
        BookingRepositoryInterface $bookingRepository,
        StoreConfigRepositoryInterface $configRepository
    ) {
        $this->middleware('auth');
        $this->filmRepository = $filmRepository;
        $this->timeRepository = $timeRepository;
        $this->comboRepository = $comboRepository;
        $this->bookingRepository = $bookingRepository;
        $this->seatRepository = $seatRepository;
        $this->userRepository = $userRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function selectSeats()
    {
        /** @var \App\Time $time */
        $time = $this->timeRepository->find(request('time'));

        /** @var \App\Film $film */
        $film = $this->filmRepository->find(request('film'));

        if ($time->getFilm()->getId() != $film->getId()) {
            return redirect(
                route(
                    'fe.filmDetail',
                    ['slug' => convert_vi_to_en($time->getFilm()->getTitle()), 'film' => $time->getFilm()]
                )
            )->with('error', __('You are trying to reach not available show time.'));
        }
        if (!$time->show()->isEnabled()) {
            return redirect(
                route(
                    'fe.filmDetail',
                    ['slug' => convert_vi_to_en($time->getFilm()->getTitle()), 'film' => $time->getFilm()]
                )
            )->with('error', __('The show is not available for now. Please try again later.'));
        }

        $check = $this->isAvailableShowTime($time, $film);
        if ($check !== null) {
            return $check;
        }

        if (!$film->isOpenSaleTicket() || !$film->isVisible()) {
            return redirect(route('frontend.home'));
        }

        $combos = $this->comboRepository->getVisible()->get();

        broadcast(new \App\Events\JoinedCustomer($time))->toOthers();

        /** @var \App\Show $show */
        $show = $time->show();

        $bookedSeats = $film->getSelectedSeats($time->getStartDate(), $time->getStartTime());

        $countSeat = $this->checkSelectedSeats($show, $bookedSeats);

        $exceptShows = [$show->getId()];

        $momoPaymentMethod = $this->getMomoPaymentMethod();

        if ($countSeat > 0) {
            $seats = $show->seats()
                ->orderByDesc('row')
                ->orderByDesc('number')
                ->get();
            $seats = $seats->groupBy('row');
            return view('frontend.booking.show', compact('momoPaymentMethod','film', 'time', 'show', 'seats', 'bookedSeats', 'combos'));
        }

        $flag = true;
        while ($flag) {
            /** @var \App\Show $unSelectShow */
            $unSelectShow = $film->getFirstShowsExceptShowId($exceptShows);
            if ($unSelectShow) {
                $countSeat = $this->checkSelectedSeats($unSelectShow, $bookedSeats);
                if ($countSeat > 0) {
                    $show = $unSelectShow;
                    $seats = $show->seats()
                        ->orderByDesc('row')
                        ->orderByDesc('number')
                        ->get();
                    $seats = $seats->groupBy('row');
                    return view(
                        'frontend.booking.show',
                        compact('film', 'time', 'show', 'seats', 'bookedSeats', 'combos')
                    );
                } else {
                    array_push($exceptShows, $unSelectShow->getId());
                }
            } else {
                return redirect(
                    route('fe.filmDetail', ['slug' => convert_vi_to_en($film->getTitle()), 'film'=> $film])
                );
            }
        }
    }

    /**
     * The show time is available to reach
     *
     * @param \App\Time $time
     * @param \App\Film $film
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    private function isAvailableShowTime(\App\Time $time, \App\Film $film)
    {
        $redirect = null;
        if (\Carbon\Carbon::now()
            ->greaterThanOrEqualTo(\Carbon\Carbon::make($time->getStartDate() . $time->getStartTime())) ||
            !$time->getSchedule()->isVisible()
        ) {
            $redirect = redirect(
                route('fe.filmDetail', ['slug' => convert_vi_to_en($film->getTitle()), 'film' => $film])
            )->with('error', __('You are trying to reach not available show time.'));
            return $redirect;
        }
        return $redirect;
    }

    /**
     * @param \App\Show $show
     * @param array $bookedSeats
     * @return int
     */
    public function checkSelectedSeats(\App\Show $show, array $bookedSeats)
    {
        $countSeat = $show->seats()->count();

        /** @var \App\Seat $seat */
        foreach ($show->seats as $seat) {
            if (in_array($seat->getId(), $bookedSeats)) {
                $countSeat--;
            }
        }

        return $countSeat;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getPayment()
    {
        try {
            $orderId = 'DM' . time();
            $data = request()->all();

            /** @var \App\Time $time */
            $time = $this->timeRepository->find($data['time']['id']);
            if (\Carbon\Carbon::now()
                ->greaterThanOrEqualTo(\Carbon\Carbon::make($time->getStartDate() . $time->getStartTime()))) {
                throw new \Exception(__('You are trying to reach not available show time.'));
            }
            /** @var \App\User $user */
            $user = auth()->user();
            if ($time) {
                /** @var Booking $booking */
                $booking = $user->bookings()->create([
                    Booking::STATUS => -1,
                    Booking::BOOKING_CODE => $orderId,
                    Booking::QTY => count($data['seats']),
                    Booking::AMOUNT => $data['totalPrice'],
                    Booking::MESSAGE => __('Transaction init'),
                    Booking::COMBO => array_key_exists('combo', $data) ? $data['combo']['id'] : null,

                ]);

                if ($booking) {
                    foreach ($data['seats'] as $seat) {
                        $seat = $seat['seat'];
                        $ticket = $booking->tickets()->create([
                            Ticket::STATUS => Ticket::NOT_AVAILABLE,
                            Ticket::TICKET_CODE => uniqid('DMT'),
                            Ticket::PRICE => $seat['type'] == 0 ? 45000 : ($seat['type'] == 1 ? 55000 : 90000),
                            Ticket::SEAT => $seat['id'],
                            Ticket::TIME => $time->getId()
                        ]);

                        if (!$ticket) {
                            throw new \Exception(__('Something went wrong! Please try again.'));
                        }
                    }

                    $momoPaymentMethod = $this->getMomoPaymentMethod();
                    if (!$momoPaymentMethod || !$momoPaymentMethod["status"]) {
                        throw new UnknownException(__("No payment method available. Please try again!"));
                    }

                    $endpoint = $momoPaymentMethod["end_point"];
                    $partnerCode = $momoPaymentMethod["partner_code"];
                    $accessKey = $momoPaymentMethod["access_key"];
                    $secretKey = $momoPaymentMethod["secret_key"];
                    $amount = request('totalPrice');
                    $requestId = 'DM'.time();

                    $orderInfo = __('Payment for :name', ['name' => $orderId]);
                    $returnUrl = config('app.momo.callback');
                    $notifyurl = config('app.momo.notify');
                    $extraData = "customerEmail=".$user->getEmail();
                    $requestType = config('app.momo.request_type');

                    $rawHash = "partnerCode=" . $partnerCode .
                        "&accessKey=" . $accessKey .
                        "&requestId=" . $requestId .
                        "&amount=" . $amount.
                        "&orderId=" . $orderId .
                        "&orderInfo=" . $orderInfo .
                        "&returnUrl=" . $returnUrl .
                        "&notifyUrl=" . $notifyurl .
                        "&extraData=" . $extraData;
                    $signature = hash_hmac("sha256", $rawHash, $secretKey);
                    $data = [
                        'partnerCode' => $partnerCode,
                        'accessKey' => $accessKey,
                        'requestId' => $requestId,
                        'amount' => $amount,
                        'orderId' => $orderId,
                        'orderInfo' => $orderInfo,
                        'returnUrl' => $returnUrl,
                        'notifyUrl' => $notifyurl,
                        'extraData' => $extraData,
                        'requestType' => $requestType,
                        'signature' => $signature
                    ];
                    $jsonResult = $this->execPostRequest($endpoint, json_encode($data));

                    $result = json_decode($jsonResult, true);
                    return $result['payUrl'];
                }
                throw new UnknownException(__('Something went wrong! Please try again.'));
            }
            throw new UnknownException(__('Something went wrong! Please try again.'));
        } catch (\Exception $exception) {
            return request()->ajax() ?
                response()->json([
                    'status' => 404,
                    'message' => $exception->getMessage()
                ]): back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Send post request to endpoint url with json data
     *
     * @param string $url
     * @param $data
     * @return bool|string
     */
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback()
    {
        try {
            /** @var Booking $booking */
            $booking = $this->bookingRepository
                ->getFilter(null, [Booking::BOOKING_CODE => request('orderId')])->first();

            if ($booking) {
                if ((int)request('errorCode') === Booking::SUCCESS) {
                    $redirect = redirect(route('bookings.result', ['slug' => __('success.html')]))
                        ->with('success', __(
                            'Your booking :name has created.',
                            ['name' => $booking->getBookingCode()]
                        ));
                    $booking->tickets()->update([
                        Ticket::STATUS => Ticket::ENABLE
                    ]);
                    /** @var Ticket $ticket */
                    foreach ($booking->getTickets() as $ticket) {
                        $time = $ticket->getTime();
                        $seats[] = $ticket->getSeat()->getId();
                    }
                    broadcast(new NewBookingEvent($time, $seats))->toOthers();
                } else {
                    $booking->tickets()->update([
                        Ticket::STATUS => Ticket::NOT_AVAILABLE
                    ]);
                    $redirect = redirect(route('bookings.result', ['slug' => __('failed.html')]))
                        ->with('error', __('Transaction failed'));
                }
                $this->bookingRepository->update(null, $booking, [
                    Booking::STATUS => request('errorCode'),
                    Booking::MESSAGE => request('message')
                ]);
                return $redirect;
            }
            throw new \Exception(__('Something went wrong! Please try again.'));
        } catch (\Exception $e) {
            return redirect(route('bookings.result', ['slug' => __('failed.html')]))
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResult()
    {
        return view('frontend.booking.result');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function selectSeat()
    {
        $time = $this->timeRepository->find(request('time'));
        $seat = (request('seat'));
        $seatData['seat'] = $this->seatRepository->find($seat['seat']);
        $seatData['user'] = $this->userRepository->find($seat['user']);
        // broadcast(new NewBookingEvent($time, $seat))->toOthers();
        broadcast(new SeatSelectedStatus($time, $seatData))->toOthers();

        return collect(['status' => 200]);
    }

    public function sendSelectedSeats()
    {
        if (request()->has('data')) {
            $time = $this->timeRepository->find(request('time'));
            $dataArr = request('data');
            $data = [];
            foreach ($dataArr as $item) {
                $seatData['seat'] = $this->seatRepository->find($item['seat']);
                $seatData['user'] = $this->userRepository->find($item['user']);
                array_push($data, $seatData);
            }
            $joiner = request('joiner');
            broadcast(new NewJoinerEvent($time, $data, $joiner));
        }

        return collect(['status' => 200]);
    }

    protected function getMomoPaymentMethod()
    {
        $paymentConfig = $this->configRepository
            ->getFilter(null, [Config::SECTION_ID => Config::SALES_PAYMENT_METHOD_SECTION_ID])
            ->first();
        if ($paymentConfig) {
            $paymentConfigVal = $paymentConfig->getConfigValues();
            $momoPaymentConfigVal = $paymentConfigVal["momo"] ?? null;
            $momoData['status'] = $momoPaymentConfigVal['status'] ?? 0;
            $momoData['partner_code'] = $momoPaymentConfigVal['partner_code'] ?? null;
            $momoData['access_key'] = $momoPaymentConfigVal['access_key'] ?? null;
            $momoData['end_point'] = $momoPaymentConfigVal['end_point'] ?? null;
            $momoData['secret_key'] =  $momoPaymentConfigVal['secret_key'] ?? null;
            return $momoData;
        }
        return null;
    }
}
