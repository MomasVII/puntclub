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

    $('.new_bet_btn').click(function() {
        $('#new-bet').fadeIn('fast');
        $('#new-bet .popup').animate({top:0},{duration:600});
    });
    $('.cancel_new-bet').click(function() {
        $('#new-bet').fadeOut('fast');
        $('#new-bet .popup').animate({top:"110vh"},{duration:400});
    });

    $('.tab_headers h3').click(function() {
        $('.tab_headers h3').removeClass("active");
        $(this).addClass("active");
        if($(this).html() == "NEXT UP") {
            $(".punter_list").animate({right:0},{duration:300});
            $(".current_punters_list").animate({opacity:0},{duration:300});
            $(".next_up_punters_list").animate({opacity:1},{duration:300});
        } else if($(this).html() == "CURRENT PUNTERS") {
            $(".punter_list").animate({right:-200},{duration:300});
            $(".current_punters_list").animate({opacity:1},{duration:300});
            $(".next_up_punters_list").animate({opacity:0},{duration:300});
        }
    });

    $('.table_headers h3').click(function() {
        $('.table_headers h3').removeClass("active");
        $(this).addClass("active");
        if($(this).html() == "LEADERBOARDS") {

        } else if($(this).html() == "GRAPHS") {

        } else if($(this).html() == "AWARDS") {

        }
    });

    $('#table_id').DataTable({
        paging: false,
        searching: false,
        "responsive": true,
        "order": [[ 1, "desc" ]],
        "bInfo" : false,
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ]
    });


    //Submit new bet form
    /*$("#new_bet_submit").click(function(){
         $("#submit_me").click();
    });*/

});
