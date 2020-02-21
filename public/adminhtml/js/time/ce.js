$(document).ready(function () {
    "use strict";

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

    $('#prepare_time').on('change keyup mousewheel', function (e) {
        /* If is mouse wheel then disable increase value */
        if (e.type === 'mousewheel') {
            this.blur();
        }

        let startTimeVal = $('#start_time').val();
        if (moment(startTimeVal, 'HH:mm').isValid()) {
            let stopTimeVal = moment(startTimeVal, 'HH:mm'),
                stopTime = $('.stop_time'),
                totalTime = $('#total_time'),
                moreTime = $(this).val(),
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
});
