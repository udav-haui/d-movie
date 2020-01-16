$(document).ready(function () {
    console.log('jQuery loaded');

    /**
     * Change tab active
     */
    $('.customtab li.tab').on('click', function () {
        $('.customtab li.active').removeClass('active');
        $(this).addClass('active');
    });
});
