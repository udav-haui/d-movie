$(document).ready(function () {
    'use strict';

    if(window.location.hash !== "") {
        $('a[href="' + window.location.hash + '"]').trigger('click');
    }

    let dateNow = new Date();

    let $datepicker = $('[dmovie-datepicker]');
    /* Datepicker Initialization */
    $datepicker.datepicker({
        classes: 'select-none dmovie-border dmovie-datepicker',
        autoClose: true,
        dateFormat: 'dd-mm-yyyy',
        maxDate: dateNow
    }).each(function () {
        if ($(this).val()) {
            let date = moment($(this).val(), 'DD-MM-YYYY').toDate();
            $(this).data('datepicker')
                .selectDate(date);
        }
    });
});
