$(document).ready(function () {
    "use strict";

    $('[dmovie-select2]').select2({
        width: '100%',
        containerCssClass: ' dmovie-border',
    });

    /* Datepicker Initialization */
    $('[dmovie-datepicker]').datepicker({
        classes: 'select-none dmovie-border dmovie-datepicker',
        autoClose: true,
        dateFormat: 'dd-mm-yyyy'
    }).each(function () {
        if ($(this).val()) {
            let date = moment($(this).val(), 'DD-MM-YYYY').toDate();
            $(this).data('datepicker')
                .selectDate(date);
        }
    });

    $('[dmovie-clockpicker]').clockpicker({
        /* Init stop time */
        afterDone: function () {
            let startTimeVal = $('#start_time').val(),
                stopTime = $('.stop_time'),
                totalTime = $('#total_time'),
                moreTime = $('#prepare_time').val(),
                filmDuration = $('.film_duration').val(),
                stopTimeVal = moment(startTimeVal, 'HH:mm'),
                minCount = 0;
            if (!isNaN(parseInt(filmDuration))) {
                stopTimeVal = stopTimeVal.add(filmDuration, 'minutes');
                minCount += parseInt(filmDuration);
            }

            if (!isNaN(parseInt(moreTime))) {
                stopTimeVal = stopTimeVal.add(moreTime, 'minutes');
                minCount += parseInt(moreTime);
            }
            stopTime.val(stopTimeVal.format('HH:mm'));
            totalTime.val(minCount);
        }
    });

    $('.film_duration').on('change', function () {
        let startTimeVal = $('#start_time').val();
        if (moment(startTimeVal, 'HH:mm').isValid()) {
            let stopTimeVal = moment(startTimeVal, 'HH:mm'),
                stopTime = $('.stop_time'),
                moreTime = $('#prepare_time').val(),
                totalTime = $('#total_time'),
                filmDuration = $(this).val(),
                minCount = 0;

            if (!isNaN(parseInt(moreTime))) {
                stopTimeVal = stopTimeVal.add(moreTime, 'minutes');
                minCount += parseInt(moreTime);
            }

            stopTimeVal = stopTimeVal.add(filmDuration, 'minutes');
            totalTime.val(minCount + parseInt(filmDuration));

            stopTime.val(stopTimeVal.format('HH:mm'));
        }
    });

    $('#prepare_time').on('change keyup mousewheel', function (e) {
        /* If is mouse wheel then disable increase value */
        if (e.type === 'mousewheel') {
            this.blur();
        }

        let startTimeVal = $('#start_time').val();
        if (moment(startTimeVal, 'HH:mm').isValid()) {
            let stopTimeVal = moment(startTimeVal, 'HH:mm'),
                stopTime = $('.stop_time'),
                moreTime = $(this).val(),
                totalTime = $('#total_time'),
                filmDuration = $('.film_duration').val(),
                minCount = 0;

            if (!isNaN(parseInt(filmDuration))) {
                stopTimeVal = stopTimeVal.add(filmDuration, 'minutes');
                minCount += parseInt(filmDuration);
            }


            stopTimeVal = stopTimeVal.add(moreTime, 'minutes');
            totalTime.val(minCount + parseInt(moreTime));

            stopTime.val(stopTimeVal.format('HH:mm'));
        }
    });

    let $show = $('#show'),
        $cinema = $('#cinema'),
        $film = $('#film'),
        oldShow = $show.attr('old-value') || null,
        oldCinema = $cinema.attr('old-value') || null,
        oldFilm = $film.attr('old-value') || null,
        selectShowPlaceHolderText = $show.attr('sl2-select-show-placeholder-text'),
        selectCinemaPlaceHolderText = $cinema.attr('sl2-select-cinema-placeholder-text'),
        selectFilmPlaceHolderText = $film.attr('sl2-select-film-placeholder-text'),
        cinemaInvalidClass = $cinema.hasClass('invalid') ? 'invalid border-none' : '',
        showInvalidClass = $show.hasClass('invalid') ? 'invalid border-none' : '',
        filmInvalidClass = $film.hasClass('invalid') ? 'invalid border-none' : '',
        data = '';

    /* Fetch old cinema */
    if (oldCinema !== null) {
        ajaxRequest(
            route('cinemas.getCinema', {cinema: oldCinema}),
            'GET',
            {},
            function () {
                screenLoader()
            },
            function (res) {
                let $selectedCinema = $("<option selected='selected'></option>").val(oldCinema).text(res.data.name);
                /* Append selected option and trigger select */
                $cinema.append($selectedCinema).trigger('change').trigger('select2:select');
            },
            function (res) {
                console.log(res.responseJSON.message);
            },
            function () {
                screenLoader(0);
            }
        )
    }

    /* Fetch old show */
    if (oldShow !== null) {
        ajaxRequest(
            route('shows.getShow', {show: oldShow}),
            'GET',
            {},
            function () {
                screenLoader()
            },
            function (res) {
                let $selectedShow = $("<option selected='selected'></option>").val(oldShow).text(res.data.name);
                /* Append selected option and trigger select */
                $show.append($selectedShow).trigger('change').trigger('select2:select');
            },
            function (res) {
                console.log(res.responseJSON.message);
            },
            function () {
                screenLoader(0);
            }
        )
    }

    /* Fetch old film */
    if (oldFilm !== null) {
        ajaxRequest(
            route('films.getFilm', {film: oldFilm}),
            'GET',
            {},
            function () {
                screenLoader()
            },
            function (res) {
                let $selectedFilm = $("<option selected='selected'></option>")
                    .val(oldFilm)
                    .text(res.data.title)
                    .attr('data-running_time', res.data.running_time);
                /* Append selected option and trigger select */
                $film.append($selectedFilm).change();
                $('.film_duration').val(res.data.running_time);
            },
            function (res) {
                console.log(res.responseJSON.message);
            },
            function () {
                screenLoader(0);
            }
        )
    }

    if ($film.length > 0)
    {
        svSideSelect2(
            route('films.attemptSelect2'),
            $film,
            function (val, i) {
                return {
                    id: val.id,
                    text: val.title,
                    'data-running_time': val.running_time
                }
            },
            selectFilmPlaceHolderText,
            'dmovie-film-select2',
            filmInvalidClass
        );
    }

    $film.on('select2:select', function (e) {
        $('.film_duration').val(e.params.data["data-running_time"]).trigger('change');
    });

    if ($cinema.length > 0) {
        svSideSelect2(
            route('cinemas.attemptSelect2'),
            $cinema,
            function (val, i) {
                return {
                    id: val.id,
                    text: val.name
                };
            },
            selectCinemaPlaceHolderText,
            'dmovie-cinema-select2',
            cinemaInvalidClass
        );
    }

    $cinema.on('select2:select', function () {
        $('._notifier').hide();
        $show.prev().hide();
        svSideSelect2(
            route('shows.attemptSelect2'),
            $show,
            function (val, i) {
                return {
                    id: val.id,
                    text: val.name
                };
            },
            selectShowPlaceHolderText,
            'dmovie-show-select2',
            showInvalidClass,
            {cinema: $cinema.select2('data')[0].id}
        );
    });

    let swlErrorTitle = langTextSelector.attr('swl-error-title'),
        swlErrorText = $cinema.attr('swl-must-select-error-text');
    $show.on('select2:open', function (e) {
        if ($cinema.select2('data').length === 0) {
            normalAlert(swlErrorTitle, swlErrorText);
            $show.select2('close');
        } else {
            data = $cinema.select2('data')[0].id;
        }
    });

});
