$(document).ready(function () {

    'use strict';

    let $cinemaSelector = $('#_select-cinema'),
        selectCinemaPlaceHolder = $cinemaSelector.attr('sl2-select-cinema-placeholder');

    $cinemaSelector.select2({
        placeholder: selectCinemaPlaceHolder,
        containerCssClass: 'dmovie-sl2',
        pagination: {
            more: true,
        },
        ajax: {
            url: route('fe.cinemas.getCinemas'),
            type: 'get',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    name: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (val, i) {
                        return {
                            id: val.id,
                            text: val.name
                        };
                    })
                }
            },
            error: function (res) {
                console.log(res.responseJSON.message);
            }
        }
    });
});
