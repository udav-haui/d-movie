<?php

namespace App\Http\Controllers\Adminhtml;

use App\Booking;
use App\Dashboard;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * DashboardController constructor.
     * @param BookingRepositoryInterface $bookingRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(BookingRepositoryInterface $bookingRepository, UserRepositoryInterface $userRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->userRepository = $userRepository;
    }

    public function getDailyBookingCount($day, $conditionFields = [])
    {
        $query = Booking::query();
        if (!empty($conditionFields)) {
            foreach ($conditionFields as $fields) {
                if (count($fields) == 3) {
                    $query = $query->where($fields[0], $fields[1], $fields[2]);
                } else {
                    $query = $query->where($fields[0], $fields[1]);
                }
            }
        }

        return $query->whereDate('created_at', $day)->get()->count();
    }

    /**
     * @param $latest7Days
     * @return array
     */
    public function getAllDayOfWeek($latest7Days)
    {
        $dateArray = array();
        if (!empty($latest7Days)) {
            foreach ($latest7Days as $day) {
                $date = Carbon::make($day);
                $dayOfYear = $date->dayOfYear;
                $dateArray[$dayOfYear] = $date->format('Y-m-d');
            }
        }
        asort($dateArray);
        return $dateArray;
    }

    /**
     * @param array $dateArray
     * @param string $status
     * @return array
     */
    public function getDailyBookingCountData(array $dateArray, string $status = 'success')
    {
        $dailyBookingCountArray = array();
        $dayNameArray = array();

        $data = [
            [Booking::STATUS, Booking::SUCCESS]
        ];
        if (!empty($dateArray)) {
            foreach ($dateArray as $date) {
                if ($status != 'success') {
                    $data = [
                        [Booking::STATUS, '!=', Booking::SUCCESS]
                    ];
                }
                $bookingCount = $this->getDailyBookingCount($date, $data);
                array_push($dailyBookingCountArray, $bookingCount);
                $date = Carbon::make($date);
                $dayNo = $date->dayOfWeek;
                array_push(
                    $dayNameArray,
                    convert_locale_day_of_week($dayNo, false, false) . ' - ' . $date->format('d/m/Y')
                );
            }
        }
        $max_num = max($dailyBookingCountArray);

        $max = round(($max_num + 10/2) / 10) * 10;

        return [
            'days' => $dayNameArray,
            'booking_count' => $dailyBookingCountArray,
            'max' => $max
        ];
    }

    /**
     * Show dashboard view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        if (auth()->user()->can('view', Dashboard::class)) {
            $usersCount = $this->userRepository
                ->getFilter(null, [\App\User::ACCOUNT_TYPE=> \App\User::CUSTOMER])
                ->count();

            /** @var Collection $bookings */
            $bookings = $this->bookingRepository->all(['user']);

            $latest7DaysDump = $bookings->where('created_at', '>=', Carbon::today()->subDays(7))->pluck('created_at');

            $latest7Days = array();
            foreach ($latest7DaysDump as $day) {
                array_push($latest7Days, $day->format('Y-m-d'));
            }
//            $latest7Days = json_decode($latest7Days);

            $dateArray = $this->getAllDayOfWeek($latest7Days);


            $latest7DaysBookingSuccessCountData = $this->getDailyBookingCountData($dateArray, 'success');
            $latest7DaysBookingFailedCountData = $this->getDailyBookingCountData($dateArray, 'failed');

            $latest7DaysBooking = [
                'success' => $latest7DaysBookingSuccessCountData,
                'failed' => $latest7DaysBookingFailedCountData,
                'max' => max($latest7DaysBookingSuccessCountData['max'], $latest7DaysBookingFailedCountData['max'])
            ];

            $yesterdayIncome = $bookings->sum(function (\App\Booking $booking) {
                $createTime = $booking->getAttribute(\App\Booking::CREATED_AT)->format('Y-m-d');
                $createTime = Carbon::make($createTime);
                if ($createTime->equalTo(Carbon::yesterday()) && $booking->isSuccess()) {
                    return $booking->amount;
                }
            });

            $todayIncome = $bookings->sum(function (\App\Booking $booking) {
                $createTime = $booking->getAttribute(\App\Booking::CREATED_AT)->format('Y-m-d');
                $createTime = Carbon::make($createTime);
                if ($createTime->equalTo(Carbon::today()) && $booking->isSuccess()) {
                    return $booking->amount;
                }
            });

            $incomes = [
                'yesterday' => $yesterdayIncome,
                'today' => $todayIncome
            ];

//            dump($yesterdayIncome);
//            dump($bookings->sum('amount'));
//            dd($todayIncome);

//            dd($bookings->sum('amount'));

            return view(
                'admin.dashboard',
                compact(
                    'bookings',
                    'incomes',
                    'latest7DaysBooking',
                    'usersCount'
                )
            );
        }
        return redirect(route('users.getProfile'));
    }
}
