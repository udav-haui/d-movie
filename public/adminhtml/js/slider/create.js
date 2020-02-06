$(document).ready(function () {
    let langText = $('.lang-text');
    /** Status select2 init */
    let stateSelecttor = $('#status');
    if (stateSelecttor.length > 0) {
        stateSelecttor.select2({
            width: '100%',
            containerCssClass: ' dmovie-border',
        });
    }

    /** Dropify image */
    let imageSelector = $('.dropify');
    if (imageSelector.length > 0) {
        imageDropify(imageSelector);
    }
});
