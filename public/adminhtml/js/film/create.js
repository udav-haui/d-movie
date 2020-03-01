$(document).ready(function () {
    'use strict';

    $('[dmovie-select2]').select2({
        width: '100%',
        containerCssClass: ' dmovie-border',
    });

    imageDropify($(`.dropify`));

    $('[dmovie-tags]').tagsinput({
        tagClass: 'label label-infor dmovie-tag'
    });

    let minText = langTextSelector.attr('minutes');
    $(".vertical-spin").TouchSpin({
        verticalbuttons: true,
        verticalupclass: 'ti-plus',
        verticaldownclass: 'ti-minus',
        min: 0,
        max: 999,
        postfix: minText
    });

    /**
     * Datetime picker
     */
    let datePicker = $('[dmovie-datepicker]');
    datePicker.datepicker({
        autoClose: true,
        classes: 'select-none dmovie-border dmovie-datepicker',
        dateFormat: 'dd-mm-yyyy'
    }).each(function () {
        if ($(this).val()) {
            let date = moment($(this).val(), 'DD-MM-YYYY').toDate();
            $(this).data('datepicker')
                .selectDate(date);
        }
    });
    /** ./End datetimepicker */

    $('#mark').select2({
        containerCssClass: 'dmovie-border',
        width: '100%'
    });
});
