jQuery(document).ready(function () {
    "use strict";

    $(`[dmovie-select2]`).select2({
        width: '100%',
        containerCssClass: ' dmovie-border',
    });

    /* Summernote */
    $('[dmovie-editor]').summernote({
        height: 350, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: false, // set focus to editable area after initializing summernote
        lang: mainLang === 'vi' ? mainLang + '-VN' : mainLang
    });
});
