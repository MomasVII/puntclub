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
#   Navigation checkForResponsive
#   Navigation - scroll event - site specific function
#   Navigation - Add active nav item
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
hamburgerTransitionDuration = 0    // FLOAT Hamburger menu transition duration | Options | 0 (default)
activeItem = 0                  // INTEGER nav item child number to be made active | options | 0 (default), INTEGER
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
    this.navigationIsTop = false
    this.isResponsive = false
    this.activeItem = ( typeof options.activeItem !== 'undefined' ) ?  options.activeItem : 0

    /* Determine the currect drop down action based on responsive viewport */
    this.determineDropDownAction()

    /* Update dropdown ease from user friendly name to GSAP specific ease label */
    this.determineDropDownTransitionEase()

    /* Render fixed position on navigation if fixed passed */
    this.renderFixed()

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

    /* Debounced event for accordion menu nav item link handling */
    this.navItemHandler  = _.debounce( function(event) {
        this.accordionMenuNavItemHandler( event )
    }.bind(this))

    /* Document click function. if accordion responsive menu is open we want to close it again */
    $(document).on("click touchstart", function( event ){
        this.documentCloseResponsiveMenu( event )
    }.bind(this))

    /* Bind reveal menu style events */
    if ( this.currentNavigationStyle == "reveal" ||
         this.navigationStyle == "reveal" ||
         this.navigationStyleMedium == "reveal" ||
         this.navigationStyleSmall == "reveal" ) {
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
    #   Window Resize event
    --------------------------------------------------------------*/
    /* Check whether we have debounced resize handler available else we bind regular window resize function */
    var windowEvents = $._data(window, 'events');
    if(windowEvents && windowEvents.resizeByHandler){
        $(window).on("resizeByHandler", function () {
            this.renderFixed()
            this.determineDropDownAction()
            this.addActiveNavItem()
            TweenMax.set( $(this.target).find("nav"), { clearProps: "height" })
        }.bind(this))
    } else {
        $(window).on("resize", function() {
            this.renderFixed()
            this.determineDropDownAction()
            this.addActiveNavItem()
            TweenMax.set( $(this.target).find("nav"), { clearProps: "height" })
        }.bind(this))
    }

    /*--------------------------------------------------------------
    #   Window Scroll event
    --------------------------------------------------------------*/
    $(window).on("scroll", function () {
        var navigatoinISTop = this.scrollTop()
        if ( navigatoinISTop ) {
            this.target.closest("header").attr("data-is-top", "true");
        } else {
            this.target.closest("header").attr("data-is-top", "false");
        }

    }.bind(this))

    var navigatoinISTop = this.scrollTop()
    if ( navigatoinISTop ) {
        TweenMax.set(this.target.closest("header"), { clearProps: "background-color" })
    } else {
        TweenMax.to(this.target.closest("header"), 0, { backgroundColor: "rgba(0, 0, 0, 0.7)" })
    }

}


/*--------------------------------------------------------------
#   Navigation determine dropdown action
--------------------------------------------------------------*/
Navigation.prototype.determineDropDownAction = function() {

    /* Determin what is the current navigation style for the viewport */
    this.currentNavigationStyle = this.navigationStyle
    if ( window.matchMedia("only screen and (max-width: 1280px)").matches ) {
        this.currentNavigationStyle = this.navigationStyleMedium
    } else if ( window.matchMedia("only screen and (max-width: 640px)").matches ) {
        this.currentNavigationStyle = this.navigationStyleSmall
    }

    this.target.find("nav").attr("data-navigation-type", this.currentNavigationStyle)
    if ( this.currentNavigationStyle == "accordion" ) {

        this.target.find("nav").attr("data-expanded", "false")
        $(this.target).removeClass("menu-reveal").addClass("menu-accordion")
        this.target.find("li").removeAttr("style")
        var targetHeight = Math.floor( this.target.height() );
        this.target.find("nav").css( "top", targetHeight+"px" )

        /* Shutdown scrolling page behind the hamburger menu */
        this.target.find("nav").on( "touchmove scroll", function(event){
            event.preventDefault()
            event.stopPropagation()
        }.bind(this))

        this.target.find("a").on( "touchmove scroll", function(event){
            event.preventDefault()
            event.stopPropagation()
        }.bind(this))

    /* If current navigation style is revel set navigation */
    } else if ( this.currentNavigationStyle == "reveal" ) {
        this.target.find("nav").removeAttr("style")
        $(this.target).removeClass("menu-accordion").addClass("menu-reveal")
        this.target.find("nav").attr("data-expanded", "false")
    } else {
        this.target.find("nav").removeAttr("style")
        $(this.target).removeClass("menu-accordion menu-reveal")
        this.target.find("nav").attr("data-expanded", "false")
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
                TweenMax.fromTo( $child, thisTranstitionDuration, {
                  clip: "rect( 0, "+ $child.width() +"px, 0, 0 )" },
                  {
                    onStart: function(){ $(trigger).attr("data-expanded", "true") },
                    clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )",
                    ease: thisTransitionEase,
                    onComplete: function(){ TweenMax.set( $child, { clearProps: "all" })}
                  })
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
                TweenMax.fromTo( $child, thisTranstitionDuration, {
                  clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )" },
                  {
                    clip: "rect( 0, "+ $child.width() +"px, 0, 0 )",
                    ease: thisTransitionEase,
                    onComplete: function(){ $(trigger).attr("data-expanded", "false"); TweenMax.set( $child, { clearProps: "all" }) }
                  })
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
                TweenMax.fromTo( $child, thisTranstitionDuration,
                    { clip: "rect( 0, "+ $child.width() +"px, 0, 0 )" },
                    {
                        onStart: function(){ $(trigger).attr("data-expanded", "true") },
                        clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )",
                        ease: thisTransitionEase,
                        onComplete: function(){ TweenMax.set( $child, { clearProps: "all" })}
                    })
                TweenMax.fromTo( trigger, thisTranstitionDuration,
                    { height: trigger.height() },
                    { height: ( trigger.height() + $child.height() ), ease: thisTransitionEase })
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
                TweenMax.fromTo( $child, thisTranstitionDuration,
                    { clip: "rect( 0, "+ $child.width() +"px, "+ $child.height() +"px, 0 )" },
                    {
                        clip: "rect( 0, "+ $child.width() +"px, 0, 0 )",
                        ease: thisTransitionEase,
                        onComplete: function(){ $(trigger).attr("data-expanded", "false"); TweenMax.set( $child, { clearProps: "all" }) }
                    })
                TweenMax.fromTo( trigger, thisTranstitionDuration,
                    { height: trigger.height() },
                    {
                        height: ( trigger.height() - $child.height() ),
                        ease: thisTransitionEase,
                        onComplete: function(){ TweenMax.set( trigger, { clearProps: "all" }) }
                    })
                break
            default:
                $(trigger).attr("data-expanded", "false")
                break
        }
    }
}


/*--------------------------------------------------------------
#   Navigation - Responsive accordion menu Nav item function
--------------------------------------------------------------*/
Navigation.prototype.accordionMenuNavItemHandler = function( event ) {

    if ( this.target.find("button.nav-toggle").css("display") == "none" ) {
        return
    }

    if ( this.currentNavigationStyle != "accordion" ) {
        return
    }


    this.responsiveMenu( event )


}

/*--------------------------------------------------------------
#   Navigation .responsive-menu function
--------------------------------------------------------------*/
Navigation.prototype.responsiveMenu = function( event ) {

    if ( this.target.find("button.nav-toggle").css("display") == "none" ) {
        return
    }

    if ( this.currentNavigationStyle != "accordion" ) {
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
        if( $(event.target)[0].className === "icons icons-menu xsmall" ) {
            var thisTarget = "#" + $(event.target).closest("button.nav-toggle").attr("data-target")
            thisTarget = this.target.find(thisTarget)
        } else {
            var thisTarget = "#" + $(this.target).find("button.nav-toggle").attr("data-target")
            thisTarget = this.target.find(thisTarget)
        }


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
                    var thisHeight = 70 * $(thisTarget).find(" > ul > li").length //$(thisTarget).height() * $(thisTarget).find(" > ul > li").length // NOTE: update if nav li height is diffrent


                    TweenMax.fromTo( $child, thisTranstitionDuration,
                        { clip: "rect( 0, "+ $(window).width() +"px, 0, 0 )" },
                        {
                            onStart: function(){ $(thisTarget).attr("data-expanded", "true"); },
                            clip: "rect( 0, "+ $(window).width() +"px, "+thisHeight+"px, 0 )",
                            ease: thisTransitionEase,
                            onComplete: function(){ TweenMax.set( $child, { clearProps: "all" })}
                        })
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
        			TweenMax.fromTo( thisTarget, thisTranstitionDuration, {
                        clip: "rect( 0, "+ $(window).width() +"px, "+ thisTarget.height() +"px, 0 )" },
                        {
                            clip: "rect( 0, "+ $(window).width() +"px, 0, 0 )",
                            ease: thisTransitionEase,
                            onComplete: function(){ $(thisTarget).attr("data-expanded", "false"); TweenMax.set( thisTarget, { clearProps: "clip, height, opacity" })
                        }
                    })
                    TweenMax.to( thisTarget, ( thisTranstitionDuration - 0.15 ), { opacity: 0 })
        			TweenMax.fromTo( thisTarget, thisTranstitionDuration,
                        { height: thisHeight+"px" },
                        {
                            height: ( thisTarget.height() - thisTarget.height() ),
                            ease: thisTransitionEase,
                            onComplete: function(){ TweenMax.set( thisTarget, { clearProps: "height" })
                        }
                    })
        			break
                default:
                    break
            }

            /* IF we parsed an option of hamburger transition type animate close transition here and clean up */
            if ( thisHamburgerTransitionType !== "none" ) {
                // IF rotate clockwise or rotate counterclockwise
                if ( thisHamburgerTransitionType == "rcw" || thisHamburgerTransitionType == "rccw" ) {
                    TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {
                        rotation: 0,
                        ease: Power2.easeOut,
                        onComplete: function() { TweenMax.set(hamburgerMenu, { clearProps: "all" }) }
                    });
                } else {}
            }

        }
    }
}


/*--------------------------------------------------------------
#   Navigation reveal menu function
--------------------------------------------------------------*/
Navigation.prototype.revealMenu = function ( event ) {

    /* Handle event */
    event.stopPropagation()

    /* Set variables */
    var $element = this.target,
    navBar = this.target,
    navItem = this.target.find("nav > ul > li"),
    hamburgerMenu = this.target.find(".nav-toggle"),
    thisHamburgerTransitionType = this.hamburgerTransitionType,
    thisHamburgerTransitionDuration = this.hamburgerTransitionDuration,
    navIntro_tl = new TimelineMax({ paused: true })

    navIntro_tl.eventCallback("onComplete", function(){ TweenMax.set( navItem, { clearProps: "transform" }) } )

    /* Toggle Dropdown */
    if (  event.type == "mouseenter" || event.type == "mouseover" ) {

        var isNavigationTop = this.scrollTop()

        /* Reveal the navigation items one by one */
        TweenMax.set(navItem, { opacity: 1 })
        navIntro_tl.staggerFrom( navItem, 0.4, { y: 30, opacity: 0, ease: Power4.easeOut }, 0.05, 0 )

        if ( isNavigationTop ) {
            var backgroundTween = TweenMax.to(navBar, 0.8, { backgroundColor: "rgba(0, 0, 0, 0.7)", ease: Power2.easeOut })
            navIntro_tl.add( backgroundTween, 0.0 )
        }

        navIntro_tl.restart().play(0)

        /* IF we parsed an option of hamburger open transition type animate transition here */
        if ( thisHamburgerTransitionType !== "none" ) {
            switch ( thisHamburgerTransitionType ) {
                case "rcw": // rotate clockwise
                    TweenMax.set( hamburgerMenu, { transformOrigin: "50% 50%", force3D: "auto", transformStyle: "preserve-3d" })
                    TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: 90, ease: Power2.easeOut})
                    break
                case "rccw": // rotate counterclockwise
                    TweenMax.set( hamburgerMenu, { transformOrigin: "50% 50%", force3D: "auto", transformStyle: "preserve-3d" })
                    TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {rotation: -90, ease: Power2.easeOut})
                    break
            }
        }


    } else if ( event.type == "mouseleave" ) {

        /* Hide navigation items again */
        TweenMax.killTweensOf( navItem )
        navIntro_tl.restart().pause(0)

        var isNavigationTop = this.scrollTop()

        TweenMax.to(navItem, 0.4, {opacity: 0, ease: Power2.easeOut, onComplete: function(){ TweenMax.set( navItem, { clearProps: "all" }) } })
        if ( isNavigationTop ) {
            TweenMax.to(navBar, 0.4, { backgroundColor: "rgba(0, 0, 0, 0.0)", ease: Power2.easeOut, onComplete: function(){ TweenMax.set( navBar, { clearProps: "all" }); }  })
        }

        /* IF we parsed an option of hamburger transition type animate close transition here and clean up */
        if ( thisHamburgerTransitionType !== "none" ) {
            // IF rotate clockwise or rotate counterclockwise
            if ( thisHamburgerTransitionType == "rcw" || thisHamburgerTransitionType == "rccw" ) {
                TweenMax.to( hamburgerMenu, thisHamburgerTransitionDuration, {
                    rotation: 0,
                    ease: Power2.easeOut,
                    onComplete: function() { TweenMax.set(hamburgerMenu, { clearProps: "all" }); thisInTransition = false; }
                });
            } else {}
        }

    }
}


/*--------------------------------------------------------------
#   Navigation checkForResponsive
--------------------------------------------------------------*/
Navigation.prototype.checkForResponsive = function() {

    /* Detect mobile responsive more accurate if we have foundation */
    if ( typeof Foundation.MediaQuery.current !== 'undefined' ) {
        if ( Foundation.MediaQuery.current == "medium" || Foundation.MediaQuery.current  == "small" ) {
            this.isResponsive = true
        } else {
            this.isResponsive = false
        }
    } else if (  window.matchMedia("only screen and (max-width: 1024px)").matches ) {
        this.isResponsive = true
    } else {
        this.isResponsive = false
    }

    return this.isResponsive

}


/*--------------------------------------------------------------
#   Navigation - scroll event - site specific function
--------------------------------------------------------------*/
Navigation.prototype.scrollTop = function() {
    this.navigationIsTop = false;

    if ( $(window).scrollTop() > 0 ) {
        this.navigationIsTop = false
    } else {
        this.navigationIsTop = true
    }

    return this.navigationIsTop

}


Navigation.prototype.documentCloseResponsiveMenu = function( event ) {

    if ( this.target.find("button.nav-toggle").css("display") == "none" ) {
        return
    }

    if ( this.currentNavigationStyle != "accordion" ) {
        return
    }

    if ( window.matchMedia("only screen and (min-width: 640px) and (max-width: 1024px)").matches ) {
        if ( $(event.target).parentsUntil( $("#nav-main")).length > 3 ) {
            if ( $(this.target).find("#nav-main").attr("data-expanded") === "true" ) {
                this.responsiveMenu( event )
            }
        }
    }

}
