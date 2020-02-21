$(document).ready(function () {
    "use strict";

    $('[dmovie-select2]').select2({
        width: '100%',
        containerCssClass: ' dmovie-border',
    });

    $('#_quickmake').on('change', function () {
        if ($(this).is(':checked')) {
            $('#number').attr('disabled', true).closest('.form-group').hide();
            $('#start_at').attr('disabled', false).parent().closest('.form-group').show();
            $('#count').attr('disabled', false).parent().closest('.form-group').show();
        } else {
            $('#number').attr('disabled', false).closest('.form-group').show();
            $('#start_at').attr('disabled', true).parent().closest('.form-group').hide();
            $('#count').attr('disabled', true).parent().closest('.form-group').hide();
        }
    });
});
