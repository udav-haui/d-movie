jQuery(document).ready(function () {
    let roleSelector = $('.role-selector'),
        genderSelector = $('.gender-selector'),
        changePassChkbox = $('#changePass') || null;
    roleSelector.select2({
        containerCssClass: ' dmovie-select2-selection-border ',
        width: '100%'
    });
    genderSelector.select2({
        containerCssClass: ' dmovie-select2-selection-border ',
        width: '100%'
    });

    var maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() - 18);

    let dobDatepicker = $('#dob-datepicker');
    dobDatepicker.datepicker({
        autoClose: true,
        classes: 'select-none dmovie-border dmovie-datepicker',
        dateFormat: 'dd-mm-yyyy',
        maxDate: maxDate
    }).each(function () {
        if ($(this).val()) {
            let date = moment($(this).val(), 'DD-MM-YYYY').toDate();
            $(this).data('datepicker')
                .selectDate(date);
        }
    });
    /** ./End datetimepicker */

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
