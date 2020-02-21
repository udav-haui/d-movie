$(document).ready(function () {
    'use strict';

    $('[dmovie-select2]').select2({
        containerCssClass: ' dmovie-border ',
        width: '100%'
    });

    let dobDatepicker = $('#dob-datepicker');
    dobDatepicker.datepicker({
        autoClose: true,
        classes: 'select-none dmovie-border dmovie-datepicker',
        dateFormat: 'dd-mm-yyyy',
        maxDate: new Date()
    }).each(function () {
        if ($(this).val()) {
            let date = moment($(this).val(), 'DD-MM-YYYY').toDate();
            $(this).data('datepicker')
                .selectDate(date);
        }
    });
    /** ./End datetimepicker */


    let changePassChkbox = $('#changePass') || null;
    /** Enable to change password */
    if (changePassChkbox.length > 0) {
        let passwordInput = $('#password');
        changePassChkbox.on('change', function () {
            if ($(this).is(':checked')) {
                passwordInput.removeAttr('disabled').val('');
            } else {
                passwordInput.attr('disabled', "true");
            }
        })
    }
    /** ./End */
});
