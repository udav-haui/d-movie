jQuery(document).ready(function () {
    let roleSelector = $('.role-selector'),
        genderSelector = $('.gender-selector')
        changePassChkbox = $('#changePass') || null;
    roleSelector.select2({
        containerCssClass: ' dmovie-select2-selection-border ',
        width: '100%'
    });
    genderSelector.select2({
        containerCssClass: ' dmovie-select2-selection-border ',
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
    let dobDatepicker = $('#dob-datepicker');
    let lang = dobDatepicker.attr('lang');
    dobDatepicker.datepicker({
        autoclose: true,
        todayHighlight: true,
        language: lang,
        format: 'dd/mm/yyyy',
        endDate: '-15y'
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
