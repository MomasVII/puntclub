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
#   Document Ready Function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
    Document Ready Function
--------------------------------------------------------------*/
$(document).ready( function(){

    $(".project_a").click(function() {

        if($(this).attr("data-src") == "grattan_st") {
            $(".property-name").html("Watson Young Architects Office Refurbishment");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: Grattan &amp; Greville Sts, Prahran");
            $(".property-type").html("");
            $(".property-architect").html("Architect: Watson Young Architects <a href='https://www.watsonyoung.com.au/' target='_blank'>www.watsonyoung.com.au</a>");
            $(".property-status").html("Status: Completed");
            $(".property-description").html("Office refurbishment of Watson Young Architects office including furniture, workstations, joinery, and services to create new breakout areas and additional meeting and workspaces.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["grattan_st1", "grattan_st2", "grattan_st4", "grattan_st5" ,"grattan_st6", "grattan_st3"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/grattan_st1.jpg" class="new_bg"></div>');

        } else if($(this).attr("data-src") == "sunshine_road") {
            $(".property-name").html("Sunshine Road, Tottenham");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: Sunshine Road, Tottenham");
            $(".property-type").html("");
            $(".property-architect").html("");
            $(".property-status").html("Status: Coming Soon");
            $(".property-description").html("Design and construction of 4 office warehouse units comprising a total of 1200sqm. Construction due to commence late 2019.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["sunshine_rd1"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

        } else if($(this).attr("data-src") == "boston_road") {
            $(".property-name").html("Boston Road, Torquay");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: Boston Road, Torquay");
            $(".property-type").html("Developer: Incore <a href='http://incoredevelopments.com.au/' target='_blank'>www.incoredevelopments.com.au</a>");
            $(".property-architect").html("Architect: Milenko Podnar Architects");
            $(".property-status").html("Status: Under Construction");
            $(".property-description").html("Presently under construction for our client Incore Developments and due to be completed September 2019. Value engineering completed to ensure viability of project.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["boston_rd1"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

        } else if($(this).attr("data-src") == "melbourne_road_2") {
            $(".property-name").html("Melbourne Road, Spotswood");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: Melbourne Road, Spotswood");
            $(".property-type").html("");
            $(".property-architect").html("Architect: JMarch <a href='https://www.jmarch.com.au/' target='_blank'>www.jmarch.com.au</a>");
            $(".property-status").html("Status: Coming Soon");
            $(".property-description").html("Design and construction including planning of a Three storey multi use building consisting of one commercial level and 4 apartments over a further two levels. Construction due to commence early 2020.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["melbourne_road_2_1", "melbourne_road_2_2"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/melbourne_road_2_1.jpg" class="new_bg"></div>');

        } else if($(this).attr("data-src") == "fisher_street") {
            $(".property-name").html("65 Fischer Street, Torquay");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: 65 Fischer Street, Torquay");
            $(".property-type").html("Type: Residential");
            $(".property-architect").html("Architect: Architecton <a href='http://architecton.com.au/' target='_blank'>www.architecton.com.au</a>");
            $(".property-status").html("Status: Commencing Soon");
            $(".property-description").html("Description: Design and construct of 4No. 2-storey residential townhouses consisting of 2 and 3 bedrooms over a private garage for each unit. <br /><br />The townhouses are fitted with Fisher and Paykel appliances and Methven fixtures throughout.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["fisher_street1", "fisher_street2", "fisher_street3", "fisher_street4"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/fisher_street1.jpg" class="new_bg"></div>');

        } else if($(this).attr("data-src") == "james_court") {
            $(".property-name").html("Unit 9, 12-20 James Court, Tottenham");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: Unit 9, 12-20 James Court, Tottenham ");
            $(".property-type").html("Type: Commercial");
            $(".property-architect").html("Architect: Coming Soon");
            $(".property-status").html("Status: Coming Soon");
            $(".property-description").html("Description: Coming Soon");

            $(".hes-gallery").html("");

            /*var gallery_images = ["fisher_street1"];
            for (const s of gallery_images) {
                $(".hes-gallery").html('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image1" data-subtext="Descrition 1" data-alt="Alt 1">');
            }*/

        } else if($(this).attr("data-src") == "wallace_avenue") {
            $(".property-name").html("Wanxi Asian Convinience Store & RL Plastering Office");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: Lot 11, 22-30 Wallace Ave, Point Cook");
            $(".property-type").html("Type: Commercial");
            $(".property-architect").html("Architect: The Silver Arc <a href='http://www.thearc.com.au/' target='_blank'>https://www.thearc.com.au/</a>");
            $(".property-status").html("Status: Completed");
            $(".property-description").html("Description: Construction of a convenience store on ground and office on first level with onsite parking for customers. The project also includes a complete store fitout that includes cool rooms, display units and shelving throughout.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["wallace_ave1", "wallace_ave2", "wallace_ave3", "wallace_ave4", "wallace_ave5", "wallace_ave6", "wallace_ave7"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/wallace_ave1.jpg" class="new_bg"></div>');

        } else if($(this).attr("data-src") == "cowrie_road") {
            $(".property-name").html("31 Cowrie Road, Torquay");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: 31 Cowrie Road, Torquay");
            $(".property-type").html("Type: Residential");
            $(".property-architect").html("Architect: Latitude Architects <a href='https://www.latitudearchitects.com.au/' target='_blank'>https://www.latitudearchitects.com.au/</a>");
            $(".property-status").html("Status: Commencing Soon");
            $(".property-description").html("Description: Construction of 3No. 2 storey townhouses that consists of 3 bedrooms, 3 bathrooms and a single garage.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["cowrie_road1", "cowrie_road3", "cowrie_road4", "cowrie_road2"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/cowrie_road2.jpg" class="new_bg"></div>');

        } else if($(this).attr("data-src") == "gerves_drive") {
            $(".property-name").html("5 Gerves Drive, Werribee ");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: 5 Gerves Drive, Werribee");
            $(".property-type").html("Type: Commercial");
            $(".property-architect").html("Architect: CES Design <a href='http://www.cesdesign.com.au/' target='_blank'>http://www.cesdesign.com.au/</a>");
            $(".property-status").html("Status: Under Construction");
            $(".property-description").html("Description: Construction of 2 warehouses that includes onsite car parking for staff and loading bays. ");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["gerves_drive1"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();


        } else if($(this).attr("data-src") == "glenferrie_road") {
            $(".property-name").html("Zuccala Properties &amp; Passport Travel Office Fitout");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: 12 Glenferrie Road, Malvern");
            $(".property-type").html("Type: Commercial");
            $(".property-architect").html("Architect: Watson Young <a href='http://www.watsonyoung.com.au/' target='_blank'>https://www.watsonyoung.com.au/</a>");
            $(".property-status").html("Status: Completed");
            $(".property-description").html("Description: Construct and refurbishment of heritage overlayed building to create a new office space that includes individual offices, meeting rooms and a full servicing tearoom.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["glenferrie_road1", "glenferrie_road2", "glenferrie_road5", "glenferrie_road4", "glenferrie_road3", "glenferrie_road8",  "glenferrie_road6", "glenferrie_road7", "glenferrie_road9"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/glenferrie_road2.jpg" class="new_bg"></div>');

        } else if($(this).attr("data-src") == "melbourne_road") {
            $(".property-name").html("RPLCON Office Fitout");
            $(".property-project-name").html("");
            $(".property-project-address").html("Address: 610 Melbourne Road, Spotswood");
            $(".property-type").html("Type: Commercial");
            $(".property-architect").html("Architect: JMarch <a href='https://www.jmarch.com.au/' target='_blank'>www.jmarch.com.au</a>");
            $(".property-status").html("Status: Completed");
            $(".property-description").html("Description: Design &amp; Construction of office fitout that includes ESD principles work spaces, meeting areas, Breakout space, end of trip facilities and integrated engineering services.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["melbourne_road1", "melbourne_road4", "melbourne_road11", "melbourne_road3", "melbourne_road5", "melbourne_road8", "melbourne_road12", "melbourne_road13", "melbourne_road14", "melbourne_road10", "melbourne_road2", "melbourne_road15", "melbourne_road16"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/melbourne_road1.jpg" class="new_bg"></div>');

            HesGallery.init();
        } else if($(this).attr("data-src") == "paisley_street") {
            $(".property-name").html("Kaiseki Japanese Restaurant");
            $(".property-project-name").html("");
            $(".property-project-address").html("7 Paisley Street, Footscray");
            $(".property-type").html("Type: Commercial");
            $(".property-architect").html("");
            $(".property-status").html("Status: Completed");
            $(".property-description").html("Description: Design & Construction of a Japanese restaurant fitout across 2 levels. The fitout includes shared dining area and a function room for customers. Commercial Kitchen and custom through restaurant table exhaust to exhaust in table charcoal cooking.");

            $(".hes-gallery").html("");

            var count = 1;
            var gallery_images = ["paisley_street2", "paisley_street3", "paisley_street4", "paisley_street5", "paisley_street6", "paisley_street7", "paisley_street8", "paisley_street9", "paisley_street10", "paisley_street11", "paisley_street12", "paisley_street13"];
            gallery_images.forEach( function(s) {
                $(".hes-gallery").append('<img src="/web/image/what_we_build/'+s+'.jpg"  alt="image'+count+'" data-alt="Alt '+count+'">');
                count++;
            });

            HesGallery.init();

            $(".bg").html('<div class="new_bg_container"><img src="/web/image/what_we_build/paisley_street6.jpg" class="new_bg"></div>');
        }

        $( ".property-overlay" ).slideToggle( "slow", function() {
            var expanded_height = $(".property-overlay").height();
            var inner_height = $(".section-content").height();
            if(expanded_height > inner_height) {
                $( ".section-content" ).height( expanded_height );
            }
    });

    });

    $(".close-button").click(function() {

        $( ".section-content" ).height( "auto" );
        $( ".property-overlay" ).slideToggle( "slow", function() { });
        $( ".new_bg_container" ).fadeOut( "slow", function() {
            // Animation complete.
            $(".bg").html('');
        });

    });


    $('.coming-soon').click(function() {
        $( ".reveal-coming-soon" ).slideToggle( "slow", function() { /* Animation complete */ });
        $('.arrow1').toggleClass('rotated');
        $(this).toggleClass('color-heading');
    });

    $('.under-construction').click(function() {
        $( ".reveal-under-construction" ).slideToggle( "slow", function() { /* Animation complete */ });
        $('.arrow2').toggleClass('rotated');
        $(this).toggleClass('color-heading');
    });

    $('.completed').click(function() {
        $( ".reveal-completed" ).slideToggle( "slow", function() { /* Animation complete */ });
        $('.arrow3').toggleClass('rotated');
        $(this).toggleClass('color-heading');
    });

  $(document).foundation();

  /* Instantiate Navigation Bar */
  var navBar = new Navigation({
      target: $("#main-bar"),
      navigationStyleMedium: "accordion",
      navigationStyleSmall: "accordion",
      dropDownTransitionType: "slideDown",
      dropDownTransitionDuration: 0.7,
      dropDownTransitionEase: "softEaseOut",
	  hamburgerTransitionDuration: 0.8,
      navigationFixed: "true",
      activeItem: 3
  })

  TweenMax.to( $(".bg-yello-triangle-wrapper"), 0.6, { autoAlpha: 1, ease: Power2.easeOut });
  TweenMax.to( $(".bg-texture"), 0.6, { autoAlpha: 1, ease: Power2.easeOut });

});
