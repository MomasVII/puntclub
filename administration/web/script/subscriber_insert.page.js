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
#   Format numbers function: Format thousands commar separator per the design
-----------------------------------------------------------------------------*/
var numberWithCommas = function(number) {
    // Source: https://stackoverflow.com/questions/2901102/how-to-print-a-number-with-commas-as-thousands-separators-in-javascript#answer-2901298
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


/* set variables */
var productID = '';

/*-----------------------------------------------------------------------------
#   Update total price function
-----------------------------------------------------------------------------*/
var updateTotalPrice = function() {

    /* Set variables */
    var totalPrice = 0,
    filter = $("#filter").val(),
    needle = $("#needle").val(),
    interval = $("#interval").val(),
    $totalField = $("#total");

    $totalField.val("");
    if ( (filter  == "state" || filter  == "postcode" ) && needle == "" ) {
        $totalField.val("0");
        $('button[type="submit"]').attr("data-disabled", "true");
        return;
    }

    if ( filter == "national" ) {

        if ( interval == "") {
            $totalField.val("0");
            $('button[type="submit"]').attr("data-disabled", "true");
            return;
        }

        switch ( interval ) {
            case "OnceOff":
                totalPrice = parseInt( national_price_matrix[productID].onceoff_price );
                break;
            case "Quarterly":
                totalPrice = parseInt( national_price_matrix[productID].quarterly_price );
                break;
            case "Monthly":
                totalPrice = parseInt( national_price_matrix[productID].monthly_price );
                break;
        };

        $('button[type="submit"]').attr("data-disabled", "false");

    } else if ( filter == "state" ) {
        //state_price_matrix[3].monthly_price.ACT
        if ( interval == "") {
            $totalField.val("0");
            $('button[type="submit"]').attr("data-disabled", "true");
            return;
        }

        var stateArray = needle.split(","),
        onceoffPrice = 0,
        quarterlyPrice  = 0,
        monthlyPrice = 0;

        /* iterate through the check checked boxes array and update prices */
        for (var i = 0, len = stateArray.length; i < len; i++) {

            switch ( stateArray[i] ) {
                case "ACT":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.ACT);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.ACT);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.ACT);
                    }
                    break;
                case "NSW":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.NSW);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.NSW);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.NSW);
                    }
                    break;
                case "NT":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.NT);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.NT);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.NT);
                    }
                    break;
                case "QLD":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.QLD);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.QLD);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.QLD);
                    }
                    break;
                case "SA":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.SA);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.SA);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.SA);
                    }
                    break;
                case "TAS":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.TAS);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.TAS);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.TAS);
                    }
                    break;
                case "VIC":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.VIC);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.VIC);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.VIC);
                    }
                    break;
                case "WA":
                    if (state_price_matrix[productID].onceoff_enabled == 1) {
                        onceoffPrice += parseInt(state_price_matrix[productID].onceoff_price.WA);
                    }
                    if (state_price_matrix[productID].quarterly_enabled == 1) {
                        quarterlyPrice += parseInt(state_price_matrix[productID].quarterly_price.WA);
                    }
                    if (state_price_matrix[productID].monthly_enabled == 1) {
                        monthlyPrice += parseInt(state_price_matrix[productID].monthly_price.WA);
                    }
                    break;
            }

        }

        switch ( interval ) {
            case "OnceOff":
                totalPrice = onceoffPrice;
                break;
            case "Quarterly":
                totalPrice = quarterlyPrice;
                break;
            case "Monthly":
                totalPrice = monthlyPrice;
                break;
        };

        $('button[type="submit"]').attr("data-disabled", "false");

        //console.log( "onceoffPrice: " + onceoffPrice + " quarterlyPrice: " + quarterlyPrice + " monthlyPrice: " + monthlyPrice )
    } else if ( filter == "postcode" ) {

        if ( interval == "") {
            $totalField.val("0");
            $('button[type="submit"]').attr("data-disabled", "true");
            return;
        }

        var postcodeArray = needle.split(",");
        var postcodeCount = parseInt( postcodeArray.length - 1);

        switch ( interval ) {
            case "OnceOff":
                totalPrice = parseInt( postcode_price_matrix[productID].onceoff_price ) * postcodeCount;
                break;
            case "Quarterly":
                totalPrice = parseInt( postcode_price_matrix[productID].quarterly_price ) * postcodeCount;
                break;
            case "Monthly":
                totalPrice = parseInt( postcode_price_matrix[productID].monthly_price ) * postcodeCount;
                break;
        };

        $('button[type="submit"]').attr("data-disabled", "false");

    } else {
        if ( interval == "") {
            $totalField.val("0");
            $('button[type="submit"]').attr("data-disabled", "true");
            return;
        }
    }

    /* Update total price value */
    $totalField.val(totalPrice);

}


/*-----------------------------------------------------------------------------
#   Update national pricing matrix Function
-----------------------------------------------------------------------------*/
var updateNationalPricingMatrix = function(productID) {

    /* if national_price_matrix not set simply return */
    if ( typeof(national_price_matrix) == 'undefined' || typeof(national_price_matrix) == undefined ) {
        return;
    }
    /* If productID not set we simply return */
    if( typeof(productID) == 'undefined' || typeof(productID) == undefined ) {
        return;
    }

    /* Set root element */
    var $root = $("#byNational"),
    subscriptionTerm = subscription_term[productID];

    /* If filter is enabled we show the filter block */
    if ( national_price_matrix[productID].filter_enabled ) {

        /* Initial clean up of containers */
        $root.find('.radio-group').html('');

        if ( national_price_matrix[productID].onceoff_enabled ) {
            $root.find('.radio-group').append('<label for="nationalOnceOff"><input type="radio" id="nationalOnceOff" name="radio" required >Once off purchase<span>'+subscriptionTerm+' month(s) term of use</span></label>');
        }
        if ( national_price_matrix[productID].quarterly_enabled ) {
            $root.find('.radio-group').append('<label for="nationalQuarterly"><input type="radio" id="nationalQuarterly" name="radio">Quarterly updates<span>'+subscriptionTerm+' month(s) term of use</span></label>');
        }
        if ( national_price_matrix[productID].monthly_enabled ) {
            $root.find('.radio-group').append('<label for="nationalMonthly"><input type="radio" id="nationalMonthly" name="radio">Monthly updates<span>'+subscriptionTerm+' month(s) term of use</span></label>');
        }
        /* Show filter */
        $root.removeAttr('style');
    } else {
        /* Show filter */
        $root.css('display', 'none');
    }

}


/*-----------------------------------------------------------------------------
#   Update state pricing matrix Function
-----------------------------------------------------------------------------*/
var updateStatePricingMatrix = function(productID) {

    /* if state_price_matrix not set simply return */
    if ( typeof(state_price_matrix) == 'undefined' || typeof(state_price_matrix) == undefined ) {
        return;
    }
    /* If productID not set we simply return */
    if( typeof(productID) == 'undefined' || typeof(productID) == undefined ) {
        return;
    }
    /* Set root element */
    var $root = $("#byState"),
    subscriptionTerm = subscription_term[productID];

    if ( state_price_matrix[productID].filter_enabled ) {

        /* Initial clean up of containers */
        $root.find(".vertical-checkbox-group").html('');
        $root.find('.radio-group').html('');

        /* Set an json array obect to store our enabled states for the product */
        var enabledStatesObj;

        /*  By first checking if a pricing filter is available we then look at
        *   The pricing to get the state keys to dynamically build our states
        *   check box in the DOM; Here we also appened 'once_off', 'quarterly',
        *   'monthly' radios into the radio group;
        */
        if ( state_price_matrix[productID].onceoff_enabled ) {
            enabledStatesObj = Object.keys( state_price_matrix[productID].onceoff_price );
            $root.find('.radio-group').append('<label for="stateOnceOff"><input type="radio" id="stateOnceOff" name="radio">Once off purchase<span>'+subscriptionTerm+' month(s) term of use</span></label>')
        } else if (state_price_matrix[productID].quarterly_enabled ) {
            enabledStatesObj = Object.keys( state_price_matrix[productID].quarterly_price );
            $root.find('.radio-group').append('<label for="stateQuarterly"><input type="radio" id="stateQuarterly" name="radio">Quarterly updates<span>'+subscriptionTerm+' month(s) term of use</span></label>')
        } if (state_price_matrix[productID].monthly_enabled ) {
            enabledStatesObj = Object.keys( state_price_matrix[productID].monthly_price );
            $root.find('.radio-group').append('<label for="stateMonthly"><input type="radio" id="stateMonthly" name="radio">Monthly updates<span>'+subscriptionTerm+' month(s) term of use</span></label>')
        } else {
            return;
        }

        /* variable to store the dynamically generate DOM */
        var returnHtml = '';

        /*  If we have some enabled states we iterate through states generate a
        *   Label and dynamicall add the required chckbox elements
        */
        if ( enabledStatesObj.length > 0 ) {
            for (var i = 0, len = enabledStatesObj.length; i < len; i++) {

                /* If we are in an odd iteration we need to add a open div tag */
                if ( (i+1) % 2 == 1) {
                    returnHtml += '<div>';
                }

                returnHtml += '<label for="'+enabledStatesObj[i]+'"><input type="checkbox" id="'+enabledStatesObj[i]+'" class="stateCheckBox">'+enabledStatesObj[i]+'</label>';

                /* If we are in an even iteration or last iteration we need to add a closing div tag */
                if ( (i+1) % 2 == 0 || i == enabledStatesObj.length ) {
                    returnHtml += '</div>';
                }
            }
        }
        /* append checkbox html to the DOM */
        $root.find(".vertical-checkbox-group").append( returnHtml );
        /* Show filter */
        $root.removeAttr('style');

    } else {
        /* Show filter */
        $root.css('display', 'none');
    }

}


/*-----------------------------------------------------------------------------
#   Update postcode pricing matrix Function
-----------------------------------------------------------------------------*/
var updatePostcodePricingMatrix = function(productID) {

    /* if postcode_price_matrix not set simply return */
    if (typeof(postcode_price_matrix) == 'undefined' || typeof(postcode_price_matrix) == undefined ) {
        return;
    }
    /* If productID not set we simply return */
    if( typeof(productID) == 'undefined' || typeof(productID) == undefined ) {
        return;
    }

    /* Set root element */
    var $root = $("#byPostcode"),
    subscriptionTerm = subscription_term[productID];

    if ( postcode_price_matrix[productID].filter_enabled ) {

        /* Initial clean up of containers */
        $root.find('.radio-group').html('');

        if ( postcode_price_matrix[productID].onceoff_enabled ) {
            $root.find('.radio-group').append('<label for="postcodeOnceOff"><input type="radio" id="postcodeOnceOff" name="radio" />Once off purchase<span>'+subscriptionTerm+' month(s) term of use</span></label>');
        }
        if ( postcode_price_matrix[productID].quarterly_enabled ) {
            $root.find('.radio-group').append('<label for="postcodeQuarterly"><input type="radio" id="postcodeQuarterly" name="radio" />Quarterly updates<span>'+subscriptionTerm+' month(s) term of use</span></label>');
        }
        if ( postcode_price_matrix[productID].monthly_enabled ) {
            $root.find('.radio-group').append('<label for="postcodeMonthly"><input type="radio" id="postcodeMonthly" name="radio" />Monthly updates<span>'+subscriptionTerm+' month(s) term of use</span></label>');
        }

        /* Show filter */
        $root.removeAttr('style');
    } else {
        /* Show filter */
        $root.css('display', 'none');
    }

    /* Instantiate postcode TagBox */
    if ( $("#postcodeTagBox").length > 0 ) {
        var postcodeTagBox = new Tagbox({
            target: $("#postcodeTagBox"),
            type: "postcode"
        });
    }

}


/*-----------------------------------------------------------------------------
#   Document Ready Function
-----------------------------------------------------------------------------*/
$(document).ready( function() {


    /* Set page nav item to active */
    $("#nav-main #nav-subscribers > a").addClass("active");

    /* Instantiate flatpickr date picker */
    var currentDate = new Date();

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

    var startTime = flatpickr("#start_time", { dateFormat: "d/m/Y", defaultDate: currentDate });
    var endTime = flatpickr("#end_time", { dateFormat: "d/m/Y", defaultDate: currentDate });


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


    /*--------------------------------------------------------
    #   On change #product select update pricing matrix
    --------------------------------------------------------*/
    $(document).on("change", "#product", function() {

        productID = $(this).val();

        if ( productID != "") {
            var thiScriptionTerm = subscription_term[productID];

            var addMonths = function (date, months) {
                date.setMonth(date.getMonth() + months);
                return date;
            }
            var newDate = addMonths(new Date(), +thiScriptionTerm);
            endTime.setDate(newDate);


            updateNationalPricingMatrix(productID);
            updateStatePricingMatrix(productID);
            updatePostcodePricingMatrix(productID)
        } else {
            $('#byNational').css('display', 'none');
            $('#byState').css('display', 'none');
            $('#byPostcode').css('display', 'none');
        }

    });


    /*--------------------------------------------------------
    #   On change filer / interval radio update needle filter interval hidden fields
    --------------------------------------------------------*/
    $(document).on('change', 'input[name="radio"]', function() {

        /* Set variables */
        $input = $(this),
        filter = "",
        interval = "";

        /* Determin the filter i.e. national, state or postcode */
        /* If national radio selected */
        if( $input[0].id.indexOf('national') !== -1 ) {
            filter = 'national';
        /* If state radio selected */
        } else if ( $input[0].id.indexOf('state') !== -1 ) {
            filter = 'state';
        /* If postcode radio selected */
        } else if ( $input[0].id.indexOf('postcode') !== -1 ) {
            filter = 'postcode';
        }

        /* Determine the interval selected i.e. once off, quarterly, monthly */
        switch( $input[0].id.replace(filter, "").trim() ){
            case "OnceOff":
                interval = "OnceOff";
                break;
            case "Quarterly":
                interval = "Quarterly";
                break;
            case "Monthly":
                interval = "Monthly";
                break;
        };

        /* Clear / clean up needle field */
        if ( filter == "state" &&  ( $("#filter").val() == "postcode" ||  $("#filter").val() == "national" ) ) {
            $("#needle").val("");
            if ( $('input[class="stateCheckBox"]:checked').length > 0 ) {
                var stateValues = '';
                $('input[class="stateCheckBox"]:checked').each( function(event){
                    if ( stateValues == '' ) {
                        stateValues = stateValues + $(this).attr('id');
                    } else {
                        stateValues = stateValues + ',' + $(this).attr('id');
                    }
                });
                /* Update our needle field */
                if ( $("#needle").length > 0 ) { $("#needle").val(stateValues);  }

            } else {
                /* Update our needle field */
                if ( $("#needle").length > 0 ) { $("#needle").val('');  }
            }
        } else if ( filter == "postcode"  && ( $("#filter").val() == "state" || $("#filter").val() == "national" ) ) {
            $("#needle").val("");
            $("#needle").val( $("#postcodeTagBox .value-field").val() );
        } else if ( filter == "national" && ( $("#filter").val() == "state" || $("#filter").val() == "postcode" )  ) {
            $("#needle").val("");
        }

        /* Update hidden filter / interval fields */
        $("#filter").val(filter);
        $("#interval").val(interval);
        /* Update price */
        updateTotalPrice();

    });


    /*--------------------------------------------------------
    #   On state checkbox change update needle hidden field
    --------------------------------------------------------*/
    $(document).on("change", ".stateCheckBox", function(){

        var stateValues = '';
        if ( $('input[class="stateCheckBox"]:checked').length > 0 ) {
            $('input[class="stateCheckBox"]:checked').each( function(event){
                if ( stateValues == '' ) {
                    stateValues = stateValues + $(this).attr('id');
                } else {
                    stateValues = stateValues + ',' + $(this).attr('id');
                }
            });
            /* Update our needle field */
            if ( $("#needle").length > 0 ) { $("#needle").val(stateValues);  }

        } else {
            /* Update our needle field */
            if ( $("#needle").length > 0 ) { $("#needle").val('');  }
        }

        /* Update price */
        updateTotalPrice();

    });

    /*--------------------------------------------------------
    #   On postcode tagbox change update needle hiddne field
    --------------------------------------------------------*/
    /*  Onload detect if we can use the newer MutationObserver api
    *   to detect if the postcode tag-box tag-list field is changed
    *   or we use the now deprecated MutationEvents api
    */
    if ( window.MutationObserver && $("#postcodeTagBox .tag-list").length > 0 ) {
        var target = document.querySelector('#postcodeTagBox .tag-list');

        // create an observer instance
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation, index) {
                //console.log(mutation.type);
                /*  If we detect a change in the postcode tag box we update
                *   the hidden needle field
                */
				if ( index == 0 ) {
	                $("#needle").val("");
	                $("#needle").val( $("#postcodeTagBox .value-field").val() );
	                /* Update price */
	                updateTotalPrice();
				}
            });
        });

        // configuration of the observer:
        var config = { attributes: true, childList: true, characterData: true }

        // pass in the target node, as well as the observer options
        observer.observe(target, config);

	/*  Else MutationObserver not supported user the older
    *   MutationEvents api used for IE 9 & 10
    */
    } else if (  ( window.MutationObserver == undefined  ||  window.MutationObserver == 'undefined' ) && ( $("#postcodeTagBox .tag-list").length > 0 ) )  {

        var target = document.querySelector('#postcodeTagBox .tag-list');
		target.addEventListener("DOMSubtreeModified", function (e) {

            /*  If we detect a change in the postcode tag box we update
            *   the hidden needle field
            */
            $("#needle").val("");
            $("#needle").val( $("#postcodeTagBox .value-field").val() );
            /* Update price */
            updateTotalPrice();

        }, false);
    }


    /* Disabled default submit */
    $(document).on("click", 'button[type="submit"]', function(event){

        /* Prevent default form submit */
        event.preventDefault();

        /* Check if we have nolonger disabled button */
        if ( $(this).attr("data-disabled") == "false" ) {

            /* Submit the form */
            $(this).closest("form").submit();

	    }

    });


});


/*-----------------------------------------------------------------------------
#   Window Onload Function
-----------------------------------------------------------------------------*/
window.onload = function () {}
