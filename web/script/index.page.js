/*
  Theme Name: Raremedia PHP core_boilerplate
  Author: Lucas Jordan
  Description: Raremedia PHP core_boilerplate social index page javascript
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Homeepage
--------------------------------------------------------------*/

/*--------------------------------------------------------------
    Document Ready Function
--------------------------------------------------------------*/
$(document).ready( function(){

    $('.tab_headers h3').click(function() {
        $('.tab_headers h3').removeClass("active");
        $(this).addClass("active");
        if($(this).html() == "Next Up") {
            $(".punter_list").animate({right:100},{duration:400});
            $(".current_punters_list").animate({opacity:0},{duration:400});
            $(".next_up_punters_list").animate({opacity:1},{duration:400});
        } else if($(this).html() == "Current Punters") {
            $(".punter_list").animate({right:-100},{duration:400});
            $(".current_punters_list").animate({opacity:1},{duration:400});
            $(".next_up_punters_list").animate({opacity:0},{duration:400});
        }
    });

});
