$(document).ready(function () {
    $(`#status`).select2({
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
    let datePicker = $('#release_date');
    let lang = datePicker.attr('lang');
    datePicker.datepicker({
        autoclose: true,
        language: lang,
        format: 'dd/mm/yyyy',
        todayBtn: true,
        todayHighlight: true
    });
    /** ./End datetimepicker */

    $('#mark').select2({
        containerCssClass: 'dmovie-border',
        width: '100%'
    });
});
