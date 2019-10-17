////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Purpose: Form Handling
// Build: Rare_Site_Core_Framework
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//wait for document ready
$(document).ready(function () {

    //universal form validation and background request handling
    $("form").each(function (i, form) {
        //if foundation:abide finds form is valid
        $(form).on("submit", function (event) {

            $("#form_success").remove();
            $("#form_error").remove();

        }).on("formvalid.zf.abide", function (event, form) {

            return true;

        //if foundation:abide finds form is INvalid
        }).on("invalid.zf.abide", function (event, input) {

            event.preventDefault();
            return false;
        });
    })
});
