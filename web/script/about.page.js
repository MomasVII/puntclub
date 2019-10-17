/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia PHP core_boilerplate about page javascript
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
# Navigation
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Navigation
--------------------------------------------------------------*/
$(document).ready( function(){

    /* Instantiate Navigation Bar */
    var navBar = new Navigation({
        target: $("#main-bar"),
        navigationStyleMedium: "accordion",
        navigationStyleSmall: "accordion",
        dropDownTransitionType: "slideDown",
        dropDownTransitionDuration: 0.7,
        dropDownTransitionEase: "softEaseOut"
    })

    var brandCarousel = new Carousel({
        target: "#brandCarousel",
        carouselType: "carousel",
        autoPlay: true,
        numberOfSlides: 7,
        width: 230,
        widthMedium: 200,
        widthSmall: 150,
        marginWidth: 30,
        marginWidthMedium: 30,
        marginWidthSmall: 15,
        duration: 3
    })

});
