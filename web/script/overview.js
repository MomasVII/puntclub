/*
 Theme Name: Eastridge Entertainment Precinct
 Author: Lucas Jordan
 Description: Script to separate andd control the overview pages
 Version: 0.0.1
 Copyright: Raremedia Pty Ltd (Andrew Davidson)'
 */

/*--------------------------------------------------------------
 >>> TABLE OF CONTENTS:
 ----------------------------------------------------------------
 # Declare Variables
 # Overview Open Timeline function
 # Overview Close Timeline Function
 # Overview Open Function
 # Overview Close Function
 # Overview Event Handlers
 --------------------------------------------------------------*/


/*--------------------------------------------------------------
 # Declare Variables
 --------------------------------------------------------------*/

/* Set width variables we will use this throuhgout */
var current_width = 0,
    current_hight = 0,
    positionFromTop = 0,
    positionFromRight = 0,
    positionFromBottom = 0,
    positionFromLeft = 0,
    currentElement = '',
    overviewTransition = false,
    transitionDuration = 0.333,
    transitionDelay = transitionDuration - 0.07;
/* Varible to ensure only one overview opens at a time */


/*--------------------------------------------------------------
 # Overview Open timeline function
 --------------------------------------------------------------*/
function openTlFunction() {
    // declare open timeline
    var open_timeline = new TimelineMax({paused: true});

    /* Clean up function to remove thetimeline again */
    function openTlCleanup() {
        open_timeline.stop().clear();
        open_timeline.eventCallback("onComplete", null);
        open_timeline.eventCallback("onUpdate", null);
        open_timeline.eventCallback("onStart", null);
        $(currentElement).addClass("open");
        TweenMax.set(currentElement, {clearProps: "width, height, clip"});
        overviewTransition = false;

        return true;
    };

    TweenMax.set(currentElement, {
        display: "block",
        opacity: 1
    });

    $("html, body, .view, .page").addClass("no-scroll");

    open_timeline.from(currentElement, 0, {opacity: 0}, 0.0)
        .from(currentElement,
            transitionDuration,
            {
                clip: "rect(" + positionFromTop + "px " + positionFromRight + "px " + positionFromBottom + "px " + positionFromLeft + "px)",
                ease: Power2.easeOut,
                onComplete: openTlCleanup
            },
            0.0
        );

    open_timeline.play();

    return true;
};


/*--------------------------------------------------------------
 # Overview Close Timeline Function
 --------------------------------------------------------------*/
function closeTlFunction() {
    /* Declare close timeline */
    var close_timeline = new TimelineMax({paused: true});

    /* clean up function to remove the timeline and reset the elements */
    function closeTlCleanup() {

        close_timeline.stop().clear();
        close_timeline.eventCallback("onComplete", null);
        close_timeline.eventCallback("onUpdate", null);
        close_timeline.eventCallback("onStart", null);
        TweenMax.set(currentElement, {clearProps: "all"});
        $(currentElement).removeAttr("style");
        $(currentElement).removeClass("open");
        $("html, body, .view, .page").removeClass("no-scroll");

        return true;
    };

    close_timeline.to(currentElement, transitionDuration, {
        clip: "rect(" + positionFromTop + "px " + positionFromRight + "px " + positionFromBottom + "px " + positionFromLeft + "px)",
        ease: Power2.easeOut
    }, 0.0).to(currentElement, 0.2, {
        opacity: 0,
        ease: Power2.easeOut,
        onComplete: closeTlCleanup
    }, transitionDelay);

    close_timeline.play();

    return true;
};
window.closeTlFunction = closeTlFunction;


/*--------------------------------------------------------------
 # Overview Open function
 --------------------------------------------------------------*/
function openOverview(id, url, element) {
    // Ensure we don't fire any other events
    if (overviewTransition != false) {
        return;
    }
    if (!url) {
        url = $("#" + id).data('url');
    }

    if ( id != "iframe" ) {
        /* Load url */
        $("#" + id + " .overview-inner").load(url, function () {
            if ($(".overview.open").length > 0) {
                $(".overview.open").removeAttr("style").removeClass("open");
            }

            overviewTransition = true;

            //default overview is from window center
            positionFromTop = $(window).height() / 2;
            positionFromRight = $(window).width() / 2;
            positionFromBottom = $(window).height() / 2;
            positionFromLeft = $(window).width() / 2;

            //overview from click object
            if ($(element).length) {
                positionFromTop = $(element)[0].getBoundingClientRect().top;
                positionFromRight = $(element)[0].getBoundingClientRect().right;
                positionFromBottom = $(element)[0].getBoundingClientRect().bottom;
                positionFromLeft = $(element)[0].getBoundingClientRect().left;
            }

            currentElement = $("#" + id)

            openTlFunction();
        })
    } else if ( id == "iframe" ) {
        /* Appened iframe tag w/ url */
        var iframe = '<iframe src="' + url + '">Sorry your browser does not support inline frames.</iframe>'
         $("#" + id).find(".overview-inner").html( iframe )

        /* Open Overview */
        if ($(".overview.open").length > 0) {
            $(".overview.open").removeAttr("style").removeClass("open");
        }

        overviewTransition = true;

        //default overview is from window center
        positionFromTop = $(window).height() / 2;
        positionFromRight = $(window).width() / 2;
        positionFromBottom = $(window).height() / 2;
        positionFromLeft = $(window).width() / 2;

        //overview from click object
        if ($(element).length) {
            positionFromTop = $(element)[0].getBoundingClientRect().top;
            positionFromRight = $(element)[0].getBoundingClientRect().right;
            positionFromBottom = $(element)[0].getBoundingClientRect().bottom;
            positionFromLeft = $(element)[0].getBoundingClientRect().left;
        }

        currentElement = $("#" + id)

        openTlFunction();

    }

    return true;
};
window.openOverview = openOverview;


/*--------------------------------------------------------------
 # Overview Event Handlers
 --------------------------------------------------------------*/
function closeOverview(event) {
    // Ensure we don't fire any other events
    event.preventDefault();
    event.stopPropagation();

    closeTlFunction();

    return true;
}
/* On Click Closeoverview */
$(".overview-close").on("click touchstart", closeOverview);//current elements
$(document).on(".overview-close", "click touchstart", closeOverview);//future elements
/* Overview 2 tint touch / click event  */
$(".overview2, .overview3").on("click touchstart", function (event) {

    // Ensure we don't fire any other events
    event.preventDefault();
    event.stopPropagation();

    /* Close the overview */
    closeTlFunction();
});

/* Ensure natural overview inner behaviour */
$(".overview2 > .overview-inner").on("click touchstart", function (event) {
    event.stopPropagation();
});

/* Bind event to keypad */
$(document).keyup(function (event) {
    /* Bind Check event pressed enter */
    if (event.keyCode === 27) { // Bind event to Esc key
        if ($(".overview.open").length > 0) {
            // Esure we don't fire any other events
            event.preventDefault();
            event.stopPropagation();

            if (overviewTransition == false) {
                /* Close the overview */
                closeTlFunction();
            }

            return true/* Check if event pressed space bad */;
        }
    }
});

/* Over overview Event handler */
function clickOverview(event) {
    // Ensure we don't fire any other events
    event.preventDefault();
    event.stopPropagation();

    /* set variables */
    var overviewType = $(this).data("overview-type");
    var overviewURL = $(this).data("overview-link");
    var overViewParent = $(this).data("overview-element");

    if (overViewParent == 'this') {
        overViewParent = this
    }
    openOverview(overviewType, overviewURL, overViewParent);
}
$(document).on("click", "[data-overview]", clickOverview);
$("[data-overview]").on("click", clickOverview);

/* Event needed to stop the event propagating to the page scroller */
$("[data-overview]").on("touchstart touchend", function (event) {
    if (isResponsiveScrolling == false) {
        event.stopPropagation();
    }
});
