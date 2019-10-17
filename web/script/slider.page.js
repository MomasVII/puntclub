/*
  Theme Name: Raremedia Slider Demo
  Author: Lucas Jordan
  Description: Raremedia Slider Initialize Script
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Document Ready Function
    ## Window Resize Function
--------------------------------------------------------------*/


/*--------------------------------------------------------------
    Document Ready Function
--------------------------------------------------------------*/
$(document).ready( function(){

    var navBar = new Navigation({
        target: $("#main-bar"),
        navBarType: "accordion"
    })

    var bannerSlider = new Slider({
        target: "#sliderBanner",
        sliderType: "slider-banner",
        numberOfSlides: 2,
        stayTime: 5,
        autoPlay: true,
        autoPlayReverse: true,
        showControls: false
    })

    var contentSlider = new Slider({
        target: "#sliderContent",
        numberOfSlides: 2,
        touchControl: true
    })

    var numberSlider = new Slider({
        target: "#sliderContentNumbers",
        numberOfSlides: 3,
        navType: "numbers",
    })

});
