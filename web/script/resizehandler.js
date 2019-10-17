/*
  Theme Name: Raremedia PHO Boiler plate
  Author: Lucas Jordan
  Description: Raremedia Slider Script
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Debounced resize handler
--------------------------------------------------------------*/

/*--------------------------------------------------------------
    Debounced resize handler
--------------------------------------------------------------*/
//throttled handler for the resize
var resizeHandler = _.debounce(function () {
    $(window).trigger('resizeByHandler');
}, 50);

$(window).resize(resizeHandler);
