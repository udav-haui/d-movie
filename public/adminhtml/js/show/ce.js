$(document).ready(function () {
    $('[dmovie-select2]').select2({
        width: '100%',
        containerCssClass: ' dmovie-border',
    });

    let $cinema = $('#cinema_id'),
        oldVal = $cinema.attr('old-value') || null,
        placeHolderText = langTextSelector.attr('sl2-select-cinema-placeholder-text'),
        invalidClass = $cinema.hasClass('invalid') ? 'invalid border-none' : '';


    if (oldVal !== null) {
        ajaxRequest(
            route('cinemas.getCinema', {cinema: oldVal}),
            'GET',
            {},
            function () {
                screenLoader()
            },
            function (res) {
                let $selectedCinema = $("<option selected='selected'></option>").val(oldVal).text(res.data.name);
                $cinema.append($selectedCinema).trigger('change');
            },
            function (res) {
                console.log(res.responseJSON.message);
            },
            function () {
                screenLoader(0);
            }
        )
    }

    svSideSelect2(
        route('cinemas.attemptSelect2'),
        $cinema,
        function (val, i) {
            return {
                id: val.id,
                text: val.name
            };
        },
        placeHolderText,
        'dmovie-select2',
        invalidClass
    )
});
