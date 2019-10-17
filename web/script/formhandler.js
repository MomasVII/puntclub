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
            event.preventDefault();
            return false;
        }).on("formvalid.zf.abide", function (event, form) {

            //create post request to backend form handler
            var jqxhr = $.post("email.html", $(form).serialize(), function (data) {

                //parse JSON response
                var response = $.parseJSON(data);

                //handle repsonse state
                if (response == true) {
                    $(form).find("#form_success").fadeIn().delay(3000).fadeOut();

                    return false;
                } else {
                    $(form).find("#form_error").fadeIn().delay(3000).fadeOut();

                    return false;
                }
            })

            //watch for request failure and gracefully break
                .fail(function () {
                    $(form).find("#form_send_error").text("Unfortunately your message couldn't be sent, please try again").fadeIn().delay(3000).fadeOut();

                    return false;
                })

            //if foundation:abide finds form is INvalid
        }).on("invalid.zf.abide", function (event, input) {

            //alert user to cause of failure
            $(form).find("#form_validation_error").text("Please revise your " + $(input).attr("placeholder"));

            return false;

            //prevent submit behaviour entirely
        });
    })
});
