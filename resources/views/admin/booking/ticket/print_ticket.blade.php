<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Print Ticket') }}</title>
    <link rel="icon" type="image/png" sizes="36x36" href="{{ asset('images/logo/logo-dm-512.png') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/booking/ticket.css') }}">
</head>

<body>
<?php
    /** @var $ticket \App\Ticket */
    /** @var $booking \App\Booking */
    $printDate = \Carbon\Carbon::now();
    $printTime = $printDate;
    $staffName = $booking->getUser()->getName();
?>
@foreach ($tickets as $ticket)
    <?php
        $showName = $ticket->getSeat()->getShow()->getName();
        $seatName = $ticket->getSeat()->getSeatName();
        $cinemaName = $ticket->getCinema()->getName();
        $filmName = $ticket->getFilm()->getTitle();
        $startDate = $ticket->getTime()->getFormatStartDate();
        $startTime = $ticket->getTime()->getFormatStartTime();
        $seatType = $ticket->getSeat()->getSeatTypeLabel();
    ?>
    <div class="ticket-container">
        <div class="left">
            <div class="top">
                <div class="logo">
                    <img src="{{ asset('images/logo/logo-dm-text.png') }}" alt="dmovie">
                    <span class="company-text">{{ __('DMedia JSC') }}</span>
                </div>
                <div class="location">
                    <div class="cinema-text">
                        <div class="three-dots uppercase">{{ __('Cinema') }}: <strong>{{ $cinemaName }}</strong></div>
                        <div class="three-dots">{{ __(':floorNumber Floor, No. :buildingNumber, :streetName St., :wardName Ward, :distName Dist., :cityName', ['floorNumber' => 'xx', 'buildingNumber' => 'xx', 'streetName' => 'xxxx xxxx', 'wardName' => 'xxxx xxxx', 'distName' => 'xxx xxx', 'cityName' => __('Ha Noi')]) }}</div>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="film-name three-dots">{{ $filmName }}</div>
                <div class="time">
                    <span class="date">{{ $startDate }}</span>
                    <span class="start-time">{{ $startTime }}</span>
                </div>
                <div class="seat-type">
                    {{__('adult')}} {{ $seatType }}
                </div>
                <div class="price">{{ $ticket->getFormatPrice() }}đ</div>
                <div class="info display-flex">
                    <div class="staff-info">
                        <div class="name">{{ $staffName }}</div>
                        <div class="print-time">
                            <span class="date">{{ $printDate->format('d/m/Y') }}</span>
                            <span class="time">{{ $printTime->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="show-info display-flex">
                        <div class="seat display-flex">
                            <div class="seat-name">{{ $seatName }}</div>
                            <div class="desc">{{ __('One/Seat') }}</div>
                        </div>
                        <div class="show display-flex">
                            <div class="show-name">{{$showName}}</div>
                            <div class="desc">{{ __('Room') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom">{{ asset('') }}</div>
        </div>
        <div class="right">
            <div class="top">
                <div class="logo">
                    <img src="{{ asset('images/logo/logo-dm-text.png') }}" alt="dmovie">
                    <span class="company-text">{{ __('DMedia JSC') }}</span>
                </div>
                <div class="cinema-name">
                    {{ $cinemaName }}
                </div>
            </div>
            <div class="body">
                <div class="film-name three-dots">{{ $filmName }}</div>
                <div class="time">
                    <span class="date">{{ $startDate }}</span>
                    <span class="start-time">{{ $startTime }}</span>
                </div>
                <div class="seat-type">
                    {{__('adult')}} {{ $seatType }}
                </div>
                <div class="price">{{ $ticket->getFormatPrice() }}đ</div>
                <div class="show-info">
                    {{ __('R') }}: {{ $showName }} {{ __('S') }}: {{ $seatName }}
                </div>
                <div class="staff-info">
                    <div class="name">{{ $staffName }}</div>
                    <div class="print-time">
                        <span class="date">{{ $printDate->format('d/m/Y') }}</span>
                        <span class="time">{{ $printTime->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<script type="text/javascript" src="{{ asset('Assets/Common/Plugins/JQuery/jquery.min-3.4.1.js') }}"></script>
<script>
    $(document).ready(function () {
        window.print();
    });
</script>
</body>

</html>
