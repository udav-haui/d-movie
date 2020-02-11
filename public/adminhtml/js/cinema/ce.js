jQuery(document).ready(function () {
    $(`#status`).select2({
        width: '100%',
        containerCssClass: ' dmovie-border',
    });


    /* Summernote */
    $('#description').summernote({
        height: 350, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: false, // set focus to editable area after initializing summernote
        lang: mainLang === 'vi' ? mainLang + '-VN' : mainLang
    });

    /* Tinymce editer */
    // if ($("#description").length > 0) {
    //     tinymce.init({
    //         selector: "textarea#description",
    //         theme: "modern",
    //         height: 300,
    //         plugins: [
    //             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker", "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking", "save table contextmenu directionality emoticons template paste textcolor"
    //         ],
    //         toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
    //         language: mainLang
    //     });
    // }
});
