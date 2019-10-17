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


    $(".user-profiles").click(function() {

        $('html, body').animate({ scrollTop: 0 }, 'slow', function () { });

        if($(this).attr("data-src") == "rui") {

            jQuery("#profile_image").attr("src", "/web/image/profile03b.jpg");

            document.getElementById("user-profile-content_id").innerHTML = "<h2>Rui Lopes</h2><h2>MANAGING DIRECTOR</h2><hr /><p>Rui has 23 years of experience in the building industry, commencing in architectural drafting but then making the move to construction of high density residential projects and large scale industrial developments in Sydney. He moved to Melbourne in 2004 where he constructed numerous industrial and commercial buildings, building refurbishments and office fitouts. Whilst working full-time, he completed his Bachelor of Applied Science in Construction Management. </p><p>Today, Rui heads RPLCON as Found and Managing Director. Every project is personally overseen by Rui, ensuring his experience of various project types paired with his proactive attitude contributes to a successful building process. From his extensive experience as a Project Manager, he excels in delivering projects and ensuring expectations are met and surpassed due to his hard work and client focused building practices.</p><p>Rui is a Green Star Accredited Professional with a strong knowledge of engineering services and sustainable construction techniques further value adding to every project.</p>";

            document.getElementById("all-projects").innerHTML = '<div class="projects-listing"><div class="notable-project"><h1>ING - RIVERWOOD BUSINESS PARK<br />RIVERWOOD</h1></div><div class="project-price">$22.5M</div></div><div class="projects-listing"><div class="notable-project"><h1>COLONIAL FIRST STATE<br />PORT MELBOURNE</h1><p>Warehouse development</p></div><div class="project-price">$8.7M</div></div><div class="projects-listing"><div class="notable-project"><h1>OPSM EYE HUB<br />HAWTHORN</h1><p>MBAV Winner - Best Shop &amp; Office Fitout 2011</p></div><div class="project-price">$7.1M</div></div><div class="projects-listing"><div class="notable-project"><h1>CSC<br />DOCKLANDS</h1></div><div class="project-price">$7.0M</div></div><div class="projects-listing"><div class="notable-project"><h1>TOYOTA<br />ALTONA</h1><p><PDC>Warehouse</PDC></p></div><div class="project-price">$4.6M</div></div><div class="projects-listing"><div class="notable-project"><h1>ING - MACMILLAN</h1><p>Warehouse Extension</p></div><div class="project-price">$4.2M</div></div><div class="projects-listing"><div class="notable-project"><h1>WARREN &amp; BROWN TECHNOLOGIES<br />MAIDSTONE</h1></div><div class="project-price">$3.4M</div></div><div class="projects-listing"><div class="notable-project"><h1>FISHER &amp; PAYKEL<br />DERRIMUT</h1><p>Warehouse Extension</p></div><div class="project-price">$3.2M</div></div><div class="projects-listing"><div class="notable-project"><h1>MRC<br />RAVENHALL</h1><p>Chartwell Management Unit Conversion</p></div><div class="project-price">$1.5M</div></div>';

        } else if($(this).attr("data-src") == "david") {

            jQuery("#profile_image").attr("src", "/web/image/profile05b.jpg");

            document.getElementById("user-profile-content_id").innerHTML ="<h2>David De Luca</h2><h2>ESTIMATOR</h2><hr /><p>David has been a construction estimator for 4 years predominately working in the residential sector with recent exposure in commercial fit-out, school buildings and factory warehouse units.</p><p>David has had experience working closely with clients to help them reach their budgetary requirements by providing optional alternative finishes, alternative design and material changes where able.</p><p>He has an understanding of the National Construction Code including government public protection works and building surveyor requirements.</p><P>David has a diploma of Building and Construction (Building) Completed in 2017 from Swinburne University of Technology.</p>";

            document.getElementById("all-projects").innerHTML = '<div class="projects-listing"><div class="notable-project"><h1>10 MARRIAGE ROAD<br />BRIGHTON EAST</h1><p>Dual occupancy architecturally designed homes with a basement</p></div><div class="project-price">$2.4M</div></div><div class="projects-listing"><div class="notable-project"><h1>27 MOFFAT STREE<br />BRIGHTON</h1><p>Architecturally designed home</p></div><div class="project-price">$1.5M</div></div><div class="projects-listing"><div class="notable-project"><h1>26 BYRON STREET<br />BRIGHTON</h1><p>Architecturally designed home</p></div><div class="project-price">$1.1M</div></div><div class="projects-listing"><div class="notable-project"><h1>6 WOOD STREET<br />STRATHMORE</h1><p>Dual occupancy architecturally designed homes with a basement and elevator.</p></div><div class="project-price">$1.5M</div></div><div class="projects-listing"><div class="notable-project"><h1>2 HOPETOUN COURT<br />WEST MEADOWS</h1><p>Eight-unit investment development.</p></div><div class="project-price">$2M</div></div><div class="projects-listing"><div class="notable-project"><h1>TOLL WEB DOCK REDEVELOPMENT</h1><p>Commerical Warehouses</p></div><div class="project-price">$5.3M</div></div><div class="projects-listing"><div class="notable-project"><h1>SUNSHINE ROAD TOTTENHAM</h1><p>4 Office Warehouses</p></div><div class="project-price">$1.2M</div></div>';

        } else if($(this).attr("data-src") == "joel") {

            jQuery("#profile_image").attr("src", "/web/image/profile01b.jpg");

            document.getElementById("user-profile-content_id").innerHTML ="<h2>Joel Petty</h2><h2>SITE MANAGER</h2><hr /><p>Joel has been in the Australian building industry for 8 years, working on various sites. His experience and proficiency in commercial construction stems from his strong trade background as a qualified plumber and recent completion of Construction Management Diploma.</p><p>Coupled with his trade background, it enables him to have a strong attention to detail and comprehensive understanding on coordinating trades on site. Joel has recently completed projects at Ravenhall and Malvern.</p><p>Before joining RPLCON, Joel worked at Milcon &amp; Pinnacle plumbing.</p><p>Notable projects - Glenferrie Rd Malvern, Ravenhall Warehouses, Point Cook Convenience store.</p>";

            document.getElementById("all-projects").innerHTML = '<div class="projects-listing"><div class="notable-project"><h1>mrc<br />RAVENHALL</h1><p>Chartwell Management Unit Conversion</p></div><div class="project-price">$1.5M</div></div></div>';

        } else if($(this).attr("data-src") == "marcus") {

            jQuery("#profile_image").attr("src", "/web/image/profile06b.jpg")
            document.getElementById("user-profile-content_id").innerHTML ="<h2>Marcus Liew</h2><h2>CONTRACT ADMINISTRATOR</h2><hr /><p>Marcus has been in the industry for 6 years. He has experience in commercial, residential, and hospitality sectors.</p><p>Marcus displays his versatility and professionalism when delivering projects couple with his engineering experience and technical knowledge add further value to every project he is involved in.</p><p>Before joining RPLCON, he worked at Cenvic Construction and K&amp;K Industry as the Contract Administrator.</p>";

            document.getElementById("all-projects").innerHTML = '<div class="projects-listing"><div class="notable-project"><h1>CRYSTAL APARTMENTS<br />OVERSEA</h1><p>Consists of 250 Units &amp; 52 Townhouses</p></div><div class="project-price">$55.0M</div></div><div class="projects-listing"><div class="notable-project"><h1>ANDES APARTMENTS<br />OVERSEA</h1><p>Consists of 200 Units &amp; 12 Townhouses</p></div><div class="project-price">$33.0M</div></div><div class="projects-listing"><div class="notable-project"><h1>RIVERINA APARTMENTS<br />FOOTSCRAY</h1><p>Consists of 200 Units</p></div><div class="project-price">$35.0M</div></div><div class="projects-listing"><div class="notable-project"><h1>TONKIN<br />MELBOURNE CBD</h1><p>Hospitality Fitout</p></div><div class="project-price">$550K</div></div><div class="projects-listing"><div class="notable-project"><h1>SMIGGLE<br />RINGOWWD SHOPPING CENTRE</h1><p>Retail Store</p></div><div class="project-price">$350K</div></div><div class="projects-listing"><div class="notable-project"><h1>MUFFIN BREAK<br />RINGWOOD SHOPPING CENTRE</h1><p>Hospitality</p></div><div class="project-price">$300K</div></div><div class="projects-listing"><div class="notable-project"><h1>FONDA MEXICAN<br />BONDI, NSW</h1><p>Hospitality Fitout</p></div><div class="project-price">$680K</div></div><div class="projects-listing"><div class="notable-project"><h1>ISSHIN IZAKAYA<br />PORT MELBOURNE</h1><p>Hospitality Fitout</p></div><div class="project-price">$650K</div></div>';
        } else if($(this).attr("data-src") == "sofia") {

            $('.notable-projects').hide();

            jQuery("#profile_image").attr("src", "/web/image/profile02b.jpg")
            document.getElementById("user-profile-content_id").innerHTML ="<h2>Sofia Lopes</h2><h2>BUSINESS ADMINISTRATOR</h2><hr /><p></p>";

            document.getElementById("all-projects").innerHTML = '';

        } else if($(this).attr("data-src") == "francisco") {

            jQuery("#profile_image").attr("src", "/web/image/profile04b.jpg")
            document.getElementById("user-profile-content_id").innerHTML ="<h2>Francisco Garcia</h2><h2>PROJECT MANAGER</h2><hr /><p>Francis has 6 years' experience in the Melbourne building industry, fulfilling trade roles in estimating, contract administration and project management under tier 1 and 2 builders. His international experience combined with his mechanical engineering qualifications and background has allowed Francis to work his way up from trade roles into building roles with his aim to project manage construction projects outright.</p><p>Following are a few projects Francis has completed in his roles as trade contract administrator and project manager including commercial and high rise residential projects for Wetspot under Multiplex and working at the Metropolitan Remand Centre project managing structural steel erection for Stilcon under Lend Lease.</p><p>Francis has shown versatility and professionalism delivering projects to date and his services engineering experience and technical knowledge add further value to every project he is involved in.</p>";

            document.getElementById("all-projects").innerHTML = '<div class="projects-listing"><div class="notable-project"><h1>CONVENTION CENTRE</h1><p>Structural Steel Project Manager</p></div><div class="project-price">$15M</div></div><div class="projects-listing"><div class="notable-project"><h1>PLENTY VALLEY SHOPPING CENTRE</h1><p>Structural Steel Project Manager</p></div><div class="project-price">$4.6M</div></div><div class="projects-listing"><div class="notable-project"><h1>MRC VARIOUS WORKS</h1><p>Structural Steel Project Manager</p></div><div class="project-price">$1.7M</div></div><div class="projects-listing"><div class="notable-project"><h1>AUSTRALIA 108</h1><p>Structural Steel Project Manager</p></div><div class="project-price">$1.5M</div></div><div class="projects-listing"><div class="notable-project"><h1>WERRIBEE PLAZA</h1><p>Tiling contract administrator</p></div><div class="project-price">$4M</div></div><div class="projects-listing"><div class="notable-project"><h1>PLATINUM APARTMENTS</h1><p>Tiling Contract Administrator</p></div><div class="project-price">$1.5M</div></div><div class="projects-listing"><div class="notable-project"><h1>MARCO APARTMENTS</h1><p>Tiling Contract Administrator</p></div><div class="project-price">$1.2M</div></div><div class="projects-listing"><div class="notable-project"><h1>LAROSETTA CROWN</h1><p>Stone Cladding</p></div><div class="project-price">$1.2M</div></div><div class="projects-listing"><div class="notable-project"><h1>NAB REFURBISHMENT</h1><p>Stone and tiling</p></div><div class="project-price">$3.5M</div></div>';
        }

        $( ".user-profile-overlay" ).slideToggle( "slow", function() {

            var expanded_height = $(".user-profile-overlay").height();
            var inner_height = $(".section-content-who-we-are").height();
            if(expanded_height > inner_height) {
                $( ".section-content-who-we-are" ).height( expanded_height );
            }

        });

    });

    $(".close-button").click(function() {

        $( ".section-content-who-we-are" ).height( "auto" );

        $( ".user-profile-overlay" ).slideToggle( "slow", function() {
            // Animation complete.
            $('.notable-projects').show();
        });

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
