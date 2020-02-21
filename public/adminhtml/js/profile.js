$(document).ready(function () {

    $('#momo-payment-test').on('click', function () {
        $.ajax({
            url: route('momo.testing'),
            type: 'POST',
            dataType: 'application/json; charset=UTF-8',
            success: function (res) {
                console.log(res);
            },
        });
    });

    /**
     * Change tab active
     */
    $('.customtab li.tab').on('click', function () {
        $('.customtab li.active').removeClass('active');
        $(this).addClass('active');
    });
    let dmovieUserSelectClass = 'dmovie-users-select2';
    $('.gender-selector').select2({
        containerCssClass: dmovieUserSelectClass + ' dmovie-select2-selection-border ',
        width: '100%'
    });

    var maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() - 18);
    let dobDatepicker = $('#dob-datepicker-autoclose');
    dobDatepicker.datepicker({
        autoclose: true,
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

    /**
     * Profile uploader
     */
    let uploader = $('.avatar-dropify');
    window.parent.imageDropify(uploader);
});
