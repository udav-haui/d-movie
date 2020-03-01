$(document).ready(function () {
    'use strict';


});

function bookingSeat(self, startDate, time, film, filmName) {
    film = JSON.parse(film);
    time = JSON.parse(time);
    $('#film-name').text(filmName);
    $('#start_date').children().text(startDate);
    $('#start_time').children().text(time.start_time);
    $('#_booking-action').attr('href', route('bookings.selectSeats', {film: film.id, time: time.id}));// ['film' => $film->getId(), 'time' => $time->getId()]))
}
