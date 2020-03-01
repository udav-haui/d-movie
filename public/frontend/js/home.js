$(document).ready(function () {
    'use strict';

    if(typeof($.fn.modal) === 'undefined') {
        document.write('<script src="//www.mysite.com/js/v/bootstrap-2.1.1.js"><\/script>')
    }


});

/**
 *
 * @param filmName
 * @param filmTrailerLink
 */
function viewTrailer(filmName, filmTrailerLink) {

    let $trailerFrame = `<iframe style="width: 100%; height: 60vh" src="${filmTrailerLink}?rel=0&amp;showinfo=0&amp;autoplay=1" allowfullscreen></iframe>`;
    $('#trailer').html($trailerFrame);
    $('#film-name').html(filmName.replace("&sv&", "\"").replace("&sv&", "\""));
    $('#view-trailer-pop-up').trigger('click');
    // $('#film-name').html(filmName.replace("&sv&", "\"").replace("&sv&", "\""));
    // $('#trailer').html('<iframe id="fancybox-frame" width="100%" height="377" src="' + filmTrailerLink.replace("watch?v=", "embed/index.html") + '?rel=0&amp;showinfo=0&amp;autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');

}
