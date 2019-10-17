/*
  Theme Name: Raremedia Navigation Bar Class
  Author: Lucas Jordan
  Description: Raremedia Navigation Bar Class
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Navigation documentation
#   Navigation class constructor
#   Toggle dropdown function
#   Navigation dropdownaccordion function
#   Navigation .responsive-menu function
#   Navigation fixed function
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#   Navigation documentation
----------------------------------------------------------------
target = parent                    // STRING Navigation Bar Target the root dom element in which the Navigation object is bound to
navigationType = "top"             // STRING Navigation Type or position | Options | top (default), left, right. bottom
navigationStyle = "hover"          // STRING Navigation Bar Style for Desktop or all view ports if no option is passed to navigationStyleMedium or navigationStyleSmall | Options | hover (default), accordion, reveal ( rare.com.au style )
navigationStyleMedium = "none"     // STRING Navigation Bar Style for Medimum view ports | Options | none (default), hover, accordion
navigationStyleSmall = "none"      // STRING Navigation Bar Style for Small view ports | Options | none (default), hover, accordion
dropDownType = "dropdown"          // STRING Drop down type | Options | dropdown (default), accordion
dropDownTransitionType = "none"    // STRING Drop down transition type | Options | none (default), slideDown
dropDownTransitionDuration = 0     // FLOAT Drop down transition duration | Options | 0 (default),
dropDownTransitionEase = "none"    // STRING Drop down transition ease | Options | none(default),
dropDownIcon = "none"              // STRING Drop down Icon type if we even want to display icons on our drop downs. | Options | none (default), chevron, plusMinus
navigationFixed = false            // BOOLEAN value applies fixed to top position to the navigation bar | Options | fasle (default), true
hamburgerTransitionType = "none"   // STRING Hamburger menu transitino type | Options | none (default), rcw ( rotate clockwise ), rccw ( rotate counterclockwise )
hamburgerTransitionDuration = 0    // FLOAT Hamburger menu transition duration | Options | 0 (default),
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#  Navigation class constructor
--------------------------------------------------------------*/
function Navigation ( options ) {

    /* If no target is passed we simply return */
    if( typeof options.target === 'undefined' ) {
        return
    }

    /* Check to ensure new keyword was used to make sure obect is instance of Navigation */
    if ( !( this instanceof Navigation ) ) {
        return new Navigation( options )
    }

    /* Set the slider parameters */
    this.target = ( typeof options.target !== 'undefined' ) ?  options.target : 1
    this.navigationType = ( typeof options.navigationType !== 'undefined' ) ?  options.navigationType : "top"
    this.navigationStyle = ( typeof options.navigationStyle !== 'undefined' ) ?  options.navigationStyle : "hover"
    this.navigationStyleMedium = ( typeof options.navigationStyleMedium !== 'undefined' ) ?  options.navigationStyleMedium : "none"
    this.navigationStyleSmall = ( typeof options.navigationStyleSmall !== 'undefined' ) ?  options.navigationStyleSmall : "none"
    this.dropDownType = ( typeof options.dropDownType !== 'undefined' ) ?  options.dropDownType : "dropdown"
    this.dropDownTransitionType = ( typeof options.dropDownTransitionType !== 'undefined' ) ?  options.dropDownTransitionType : "none"
    this.dropDownTransitionDuration = ( typeof options.dropDownTransitionDuration !== 'undefined' ) ?  options.dropDownTransitionDuration : 0
    this.dropDownTransitionEase = ( typeof options.dropDownTransitionEase !== 'undefined' ) ?  options.dropDownTransitionEase : "none"
    this.dropDownIcon = ( typeof options.dropDownIcon !== 'undefined' ) ?  options.dropDownIcon : "none"
    this.navigationFixed = ( typeof options.navigationFixed !== 'undefined' ) ?  options.navigationFixed : false
    this.hamburgerTransitionType = ( typeof options.hamburgerTransitionType !== 'undefined' ) ?  options.hamburgerTransitionType : "none"
    this.hamburgerTransitionDuration = ( typeof options.hamburgerTransitionDuration !== 'undefined' ) ?  options.hamburgerTransitionDuration : 0
    this.currentNavigationStyle = this.navigationStyle

    /* Determine the currect drop down action based on responsive viewport */
    this.determineDropDownAction()

    /* Update dropdown ease from user friendly name to GSAP specific ease label */
    this.determineDropDownTransitionEase()

    /* Render fixed position on navigation if fixed passed */
    this.renderFixed()

    //this.determineResponsiveMenu()

    /* Debounce and handle all dropdown events */
    this.dropdownHandler  = _.debounce( function(event) {

        if ( this.currentNavigationStyle == "accordion" ) {
            this.dropdownaccordion(event)

        } else if ( this.currentNavigationStyle == "hover" ) {
            this.dropdownHover(event)

        } else if ( this.currentNavigationStyle == "reveal" ) {
            this.revealMenu(event)
        }

    }.bind(this), this.dropDownTransitionDuration, { leading:true, trailing:true })

    /* Bind Events base on Navigaiton Bar Type */
    /* Toggle drowdown mouseenter event */
    this.target.find("li.parent").mouseenter( function ( event ) {
        this.dropdownHandler( event )
    }.bind(this))

    /* Tggle drowdown mouseleave event */
    this.target.find("li.parent").mouseleave( function ( event ) {
        this.dropdownHandler( event )
    }.bind(this))

    /* Toggle dropdown menu accordion type nav  */
    this.target.find("li.parent").click( function ( event ) {
        if ( this.currentNavigationStyle == "accordion" ) {
            if (  $(event.target).parentsUntil("li.parent").length > 1 ) {
                event.stopPropagation()
                var thieHref = $(event.target).closest("a").attr("href")
                location.href = thieHref
            } else {
                event.preventDefault()
            }
        }
        this.dropdownHandler( event )
    }.bind(this))

    /* Double click event go to parent href value */
    this.target.find("li.parent").on( "dblclick", function ( event ) {
        var thieHref = $(event.target).closest("a").attr("href")
        location.href = thieHref
    }.bind(this))

    /* Define debounced toggle responsive menu handler */
    this.responsiveMenuHandler  = _.debounce( function(event) {

        this.responsiveMenu(event)

    }.bind(this), this.dropDownTransitionDuration, { leading:true, trailing:true })

    /* Hamburger Responsive Menu Functionality */
    this.target.find("button.nav-toggle").click( function( event ){
        this.responsiveMenuHandler( event )
    }.bind(this))

    /* Bind reveal menu style events */
    if ( this.currentNavigationStyle == "reveal" ) {
        /* Toggle reveal menu mouseenter event */
        this.target.mouseenter( function ( event ) {
            this.dropdownHandler( event )
        }.bind(this))

        /* Tggle reveal menu mouseleave event */
        this.target.mouseleave( function ( event ) {
            this.dropdownHandler( event )
        }.bind(this))
    }

    /*--------------------------------------------------------------
    #   Resize event
    --------------------------------------------------------------*/
    $(window).on("resizeByHandler", function () {
        this.renderFixed()
        this.determineDropDownAction()
    }.bind(this))
}


/*--------------------------------------------------------------
#   Navigation determine dropdown action
--------------------------------------------------------------*/
Navigation.prototype.determineDropDownAction = function() {
    if ( typeof Foundation.MediaQuery.current !== 'undefined' ) {

        switch (  Foundation.MediaQuery.current ) {
            case "medium":
                this.currentNavigationStyle = this.navigationStyleMedium
                break
            case "small":
                this.currentNavigationStyle = this.navigationStyleSmall
                break
            default:
                this.currentNavigationStyle = this.navigationStyle
                break
        }
        this.target.find("nav").attr("data-navigation-type", this.currentNavigationStyle)
        if ( this.currentNavigationStyle == "accordion" && this.target.find(".nav-toggle").css("display") != "none" ) {
            this.target.find("nav").attr("data-expanded", "false")

        /* If current navigation style is revel set navigation */
        } else if ( this.currentNavigationStyle == "reveal" ) {
            $(this.target).addClass("menu-reveal")
        } else {
            $(this.target).removeClass("menu-reveal")
        }
        this.target.find("li").removeAttr("style")
    } else {
        /* NOTE  Foundation.MediaQuery.medium = 40em or 640px */
        /* NOTE  Foundation.MediaQuery.large = 64em or 1024px */
        if ( $(window).innerWidth() > 639 ) {
            this.currentNavigationStyle = this.navigationStyleMedium
        } else if ( $(window).innerWidth() > 1023 ){
            this.currentNavigationStyle = this.navigationStyle
        } else {
            this.currentNavigationStyle = this.navigationStyleSmall
        }
    }
}


/*--------------------------------------------------------------
#   Navigation determine dropdown transition easeing
--------------------------------------------------------------*/
Navigation.prototype.determineDropDownTransitionEase = function() {
    /* TODO replace this with somekind of animation library  */
    switch( this.dropDownTransitionEase ) {
        case "none":
            this.dropDownTransitionEase = "Power0.easeNone"
            break
        case "easeOut":
            this.dropDownTransitionEase = "Power1.easeOut"
            break;
        case "softEaseOut":
            this.dropDownTransitionEase = "Power2.easeOut"
            break
        case "softerEaseOut":
            this.dropDownTransitionEase = "Power3.easeOut"
            break
        case "softestEaseOut":
            this.dropDownTransitionEase = "Power4.easeOut"
            break
        default:
            this.dropDownTransitionEase = "Power0.easeNone"
            break
    }
}


/*--------------------------------------------------------------
#   Navigation fixed function
--------------------------------------------------------------*/
Navigation.prototype.renderFixed = function() {

    if ( !this.navigationFixed ) {
        return
    } else {

        $("header").attr( "data-sticky", "true" );
        $(".content").css( "padding-top", $("header").height() )

    }
}


/*--------------------------------------------------------------
#  Toggle dropdown function
--------------------------------------------------------------*/
Navigation.prototype.dropdownHover = function( event ) {

    /* Handle event */
    event.stopPropagation()

    /* Set variables */
    var $element = this.target,
    trigger = $(event.target).closest("li.parent"),
    thisTransitionType = this.dropDownTransitionType,
    thisTranstitionDuration = this.dropDownTransitionDuration,
    thisTransitionEase = this.dropDownTransitionEase

    /* Toggle Dropdown */
    if (  event.type == "mouseenter" || event.type == "mouseover" ) {

        switch ( thisTransitionType ) {
            case "none":
                $(trigger).attr("data-expanded", "true")
                break
            case "slideDown":
                var $child = $(trigger).find("ul")
                TweenMax.fromTo( $child, thisTranstitionDuration, { clip: "rect( 0, "+ $child.width() +"px, 0, 0 )" }, { onStart: function(){ $(trigger).attr("data-expanded", "true") }, clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )", ease: thisTransitionEase, onComplete: function(){ TweenMax.set( $child, { clearProps: "all" })} })
                break
            default:
                $(trigger).attr("data-expanded", "true")
                break
        }


    } else if ( event.type == "mouseleave" ) {

        switch ( thisTransitionType ) {
            case "none":
                $(trigger).attr("data-expanded", "false")
                break
            case "slideDown":
                var $child = $(trigger).find("ul")
                TweenMax.fromTo( $child, thisTranstitionDuration, { clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )" }, { clip: "rect( 0, "+ $child.width() +"px, 0, 0 )", ease: thisTransitionEase, onComplete: function(){ $(trigger).attr("data-expanded", "false"); TweenMax.set( $child, { clearProps: "all" }) } })
                break
            default:
                $(trigger).attr("data-expanded", "false")
                break
        }

    }
}


/*--------------------------------------------------------------
#   Navigation dropdownaccordion function
--------------------------------------------------------------*/
Navigation.prototype.dropdownaccordion = function( event ) {
    /* Enforce only click events */
    if (  event.type != "click" ) {
        return
    }

    /* Handle event */
    event.preventDefault()
    event.stopPropagation()

    /* Set variables */
    var $element = this.target,
    trigger = $(event.target).closest("li.parent"),
    thisTransitionType = this.dropDownTransitionType,
    thisTranstitionDuration = this.dropDownTransitionDuration,
    thisTransitionEase = this.dropDownTransitionEase

    /* Toggle Dropdown */
    var openValue = $(trigger).attr("data-expanded")
    /* Open dropdown */
    if ( openValue == "false" ) {
        switch ( thisTransitionType ) {
            case "none":
                $(trigger).attr("data-expanded", "true")
                break
            case "slideDown":
                var $child = $(trigger).find("ul")
                TweenMax.fromTo( $child, thisTranstitionDuration, { clip: "rect( 0, "+ $child.width() +"px, 0, 0 )" }, { onStart: function(){ $(trigger).attr("data-expanded", "true") }, clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )", ease: thisTransitionEase, onComplete: function(){ TweenMax.set( $child, { clearProps: "all" })} })
                TweenMax.fromTo( trigger, thisTranstitionDuration, { height: trigger.height() }, { height: ( trigger.height() + $child.height() ), ease: thisTransitionEase })
                break
            default:
                $(trigger).attr("data-expanded", "true")
                break
        }
    /* Close dropdown */
    } else if ( openValue == "true" ) {
        switch ( thisTransitionType ) {
            case "none":
                $(trigger).attr("data-expanded", "false")
                break
            case "slideDown":
                var $child = $(trigger).find("ul")
                TweenMax.fromTo( $child, thisTranstitionDuration, { clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )" }, { clip: "rect( 0, "+ $child.width() +"px, 0, 0 )", ease: thisTransitionEase, onComplete: function(){ $(trigger).attr("data-expanded", "false"); TweenMax.set( $child, { clearProps: "all" }) } })
                TweenMax.fromTo( trigger, thisTranstitionDuration, { height: trigger.height() }, { height: ( trigger.height() - $child.height() ), ease: thisTransitionEase, onComplete: function(){ TweenMax.set( trigger, { clearProps: "all" }) } })
                break
            default:
                $(trigger).attr("data-expanded", "false")
                break
        }
    }
}


/*--------------------------------------------------------------
#   Navigation .responsive-menu function
--------------------------------------------------------------*/
Navigation.prototype.responsiveMenu = function( event ) {

    if ( this.target.find("button.nav-toggle").css("display") == "none" ) {
        return
    }

    var hamburgerMenu = this.target.find(".nav-toggle"),
    thisTransitionType = this.dropDownTransitionType,
    thisTranstitionDuration = this.dropDownTransitionDuration,
    thisTransitionEase = this.dropDownTransitionEase,
    thisHamburgerTransitionType = this.hamburgerTransitionType,
    thisHamburgerTransitionDuration = this.hamburgerTransitionDuration

    /* If we are in mobile responsive viewport */
    if ( this.target.find("button.nav-toggle").css("display") != "none" ) {

        /* Get nav-toggle target */
        var thisTarget = "#" + $(event.target).closest("button.nav-toggle").attr("data-target")
        thisTarget = this.target.find(thisTarget)

        /* Toggle nav-toggle expanded data attribute */
        if( typeof thisTarget.attr("data-expanded") === 'undefined' ) {
            thisTarget.attr("data-expanded", "false")
        }
        var openValue = thisTarget.attr("data-expanded")
        if ( openValue == "false" ) {

            switch ( thisTransitionType ) {
                case "none":
                    thisTarget.attr("data-expanded", "true")
                    break
                case "slideDown":
                    var $child = $(thisTarget).find(" > ul")
                    var thisHeight = $(thisTarget).height() * $(thisTarget).find(" > ul > li").length // NOTE: update if nav li height is differnt
                    TweenMax.fromTo( $child, thisTranstitionDuration, { clip: "rect( 0, "+ $(window).width() +"px, 0, 0 )" }, { onStart: function(){ $(thisTarget).attr("data-expanded", "true") }, clip: "rect( 0, "+ $(window).width() +"px, "+thisHeight+"px, 0 )", ease: thisTransitionEase, onComplete: function(){ TweenMax.set( $child, { clearProps: "all" })} })
                    TweenMax.fromTo( thisTarget, thisTranstitionDuration, { height: thisTarget.height() }, { height: thisHeight+"px", ease: thisTransitionEase })
                    break
                default:
                    break
            }

            /* IF we parsed an option of hamburger open transition type animate transition here */
            if ( thisHamburgerTransitionType !== "none" ) {
                switch ( thisHamburgerTransitionType ) {
                    case "rcw": // rotate clockwise
                        TweenMax.set( hamburgerMenu, { transformOrigin: "50% 50%", force3D: "auto", transformStyle: "preserve-3d" })
                        TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: 90, ease: Power2.easeOut});
                        break
                    case "rccw": // rotate counterclockwise
                        TweenMax.set( hamburgerMenu, { transformOrigin: "50% 50%", force3D: "auto", transformStyle: "preserve-3d" })
                        TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: -90, ease: Power2.easeOut});
                        break
                }
            }

        } else if ( openValue == "true" ) {

            switch ( thisTransitionType ) {
                case "none":
                    thisTarget.attr("data-expanded", "false")
                    break
                case "slideDown":
                    var $child = $(thisTarget).find(" > ul")
        			TweenMax.fromTo( $child, thisTranstitionDuration, { clip: "rect( 0, "+ $(window).width() +"px, "+ $child.height() +"px, 0 )" }, { clip: "rect( 0, "+ $(window).width() +"px, 0, 0 )", ease: thisTransitionEase, onComplete: function(){ $(thisTarget).attr("data-expanded", "false"); TweenMax.set( $child, { clearProps: "all" }) } })
        			TweenMax.fromTo( thisTarget, thisTranstitionDuration, { height: thisHeight+"px" }, { height: ( thisTarget.height() - $child.height() ), ease: thisTransitionEase, onComplete: function(){ TweenMax.set( thisTarget, { clearProps: "all" }) } })
        			break
                default:
                    break
            }

            /* IF we parsed an option of hamburger transition type animate close transition here and clean up */
            if ( thisHamburgerTransitionType !== "none" ) {
                // IF rotate clockwise or rotate counterclockwise
                if ( thisHamburgerTransitionType == "rcw" || thisHamburgerTransitionType == "rccw" ) {
                    TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: 0, ease: Power2.easeOut, onComplete: function() { TweenMax.set(hamburgerMenu, { clearProps: "all" }) } });
                } else {}
            }

        }
    }
}


/*--------------------------------------------------------------
#   Navigation .responsive-menu function
--------------------------------------------------------------*/
Navigation.prototype.revealMenu = function ( event ) {

    /* Handle event */
    event.stopPropagation()

    /* Set variables */
    var $element = this.target,
    navItem = this.target.find("nav > ul > li"),
    hamburgerMenu = this.target.find(".nav-toggle"),
    thisHamburgerTransitionType = this.hamburgerTransitionType,
    thisHamburgerTransitionDuration = this.hamburgerTransitionDuration,
    navIntro_tl = new TimelineMax({ paused: true })

    //trigger = $(event.target).closest("li.parent"),
    //thisTransitionType = this.dropDownTransitionType,
    //thisTranstitionDuration = this.dropDownTransitionDuration,
    //thisTransitionEase = this.dropDownTransitionEase

    /* Toggle Dropdown */
    if (  event.type == "mouseenter" || event.type == "mouseover" ) {

        /* Reveal the navigation items one by one */
        TweenMax.set(navItem, { opacity: 1 })
        navIntro_tl.staggerFrom( navItem, 0.4, { y: 30, opacity: 0, ease: Power4.easeOut }, 0.05, 0 )
        //.to(navBar, 0.8, {backgroundColor: "rgba(0, 0, 0, 0.7)", ease: Power2.easeOut}, 0.0);

        navIntro_tl.restart().play()

        /* IF we parsed an option of hamburger open transition type animate transition here */
        if ( thisHamburgerTransitionType !== "none" ) {
            switch ( thisHamburgerTransitionType ) {
                case "rcw": // rotate clockwise
                    TweenMax.set( hamburgerMenu, { transformOrigin: "50% 50%", force3D: "auto", transformStyle: "preserve-3d" })
                    TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: 90, ease: Power2.easeOut});
                    break
                case "rccw": // rotate counterclockwise
                    TweenMax.set( hamburgerMenu, { transformOrigin: "50% 50%", force3D: "auto", transformStyle: "preserve-3d" })
                    TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: -90, ease: Power2.easeOut});
                    break
            }
        }


    } else if ( event.type == "mouseleave" ) {

        /* Hide navigation items again */
        TweenMax.to(navItem, 0.4, {opacity: 0, ease: Power2.easeOut });

        /* IF we parsed an option of hamburger transition type animate close transition here and clean up */
        if ( thisHamburgerTransitionType !== "none" ) {
            // IF rotate clockwise or rotate counterclockwise
            if ( thisHamburgerTransitionType == "rcw" || thisHamburgerTransitionType == "rccw" ) {
                TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: 0, ease: Power2.easeOut, onComplete: function() { TweenMax.set(hamburgerMenu, { clearProps: "all" }) } });
            } else {}
        }

    }
}
