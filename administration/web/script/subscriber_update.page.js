/*
  Theme Name: Australia Post - Postcode & Movers Statistics Administration
  Author: Lucas Jordan
  Description: Reports page javascript
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Initialize Foundation
#   Format numbers function: Format thousands commar separator per the design
#   Update total price function
#   Update national pricing matrix Function
#   Update state pricing matrix Function
#   Update postcode pricing matrix Function
#   Document Ready Function
#   Window Onload Function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Initialize Foundation
--------------------------------------------------------------*/
$(document).foundation()


/*-----------------------------------------------------------------------------
#   Document Ready Function
-----------------------------------------------------------------------------*/
$(document).ready( function() {


    /* Set page nav item to active */
    $("#nav-main #nav-subscribers > a").addClass("active");



    /*--------------------------------------------------------
    #   terminate subscriber logic
    --------------------------------------------------------*/
    $("#terminate_btn").click(function(event) {
        msg = "Are you sure you wish to terminate this subscription? Note: This action is final.";
        var answer = confirm(msg);
        if (!answer) {
            event.preventDefault();
        }
    });

	/*  JS Assign polyfill for IE
    *   Source: https://stackoverflow.com/questions/35215360/getting-error-object-doesnt-support-property-or-method-assign
    */
    if (typeof Object.assign != 'function') {
        Object.assign = function(target) {
            'use strict';
            if (target == null) {
                throw new TypeError('Cannot convert undefined or null to object');
            }

            target = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var source = arguments[index];
                if (source != null) {
                    for (var key in source) {
                        if (Object.prototype.hasOwnProperty.call(source, key)) {
                            target[key] = source[key];
                        }
                    }
                }
            }
            return target;
        };
    }

    /* If we have set a correct date set the date picker to that date */
    if ( $("#lastDelivery").val() != "" ) {
        var lastDelivery =  $("#lastDelivery").val().toString();
        if ( lastDelivery.indexOf( "1970" ) != -1) {
            var currentDate = new Date( $("#lastDelivery").val() );
        	$("#lastDelivery").flatpickr({
                dateFormat: "d/m/Y",
                defaultDate: currentDate
            });
        /* Else Initialize the date picks but don't select a date */
        } else {
        	$("#lastDelivery").flatpickr({
                dateFormat: "d/m/Y"
            });
        }
    } else {
        $("#lastDelivery").flatpickr({
            dateFormat: "d/m/Y"
        });
    }

    /* Function to trigger wait spinner and turn it off again after 400ms */
	var callToActionSpinner = function () {
		$("#waitSpinner").addClass("fixed");
		setTimeout( function(){
            $("#waitSpinner").removeClass("fixed");
        }, 1000);
	};

    /* On click of Download data as file trigger wait spinner */
	$(document).on("click", "#download_data", function(){
		callToActionSpinner();
	});

    /* On click of Download invoice trigger wait spinner */
    $(document).on("click", "#download_invoice", function(){
		callToActionSpinner();
	});


});


/*-----------------------------------------------------------------------------
#   Window Onload Function
-----------------------------------------------------------------------------*/
window.onload = function () {}
