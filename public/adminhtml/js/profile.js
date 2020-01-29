$(document).ready(function () {
    console.log('jQuery loaded');

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

    /**
     * Datetime picker
     */
    $.fn.datepicker.dates['vi'] = {
        days: ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"],
        daysShort: ["CN", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7"],
        daysMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
        months: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
        monthsShort: ["Thg.1", "Thg.2", "Thg.3", "Thg.4", "Thg.5", "Thg.6", "Thg.7", "Thg.8", "Thg.9", "Thg.10", "Thg.11", "Thg.12"],
        today: "Hôm nay",
        clear: "Xoá",
        format: "mm/dd/yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 0
    };
    let dobDatepicker = $('#dob-datepicker-autoclose');
    let lang = dobDatepicker.attr('lang');
    dobDatepicker.datepicker({
        autoclose: true,
        todayHighlight: true,
        language: lang,
        format: 'dd/mm/yyyy',
        endDate: '-15y'
    });
    /** ./End datetimepicker */

    /**
     * Profile uploader
     */
    let uploader = $('.avatar-dropify');
    uploader.dropify({
        messages: {
            default: uploader.attr('msg-default'),
            replace: uploader.attr('msg-replace'),
            remove: uploader.attr('msg-remove'),
            error: uploader.attr('msg-error')
        }
    });
});
