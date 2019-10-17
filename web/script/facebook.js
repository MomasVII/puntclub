/*
  Theme Name: Raremedia core boilerplate
  Author: Lucas Jordan
  Description: Raremedia facebook js inplementation
  Version: 0.0.1
  Copyright: Raremedia Pty Ltd (Andrew Davidson)'
*/

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   Facebook JS Documentation
#   Facebook js SDK variables / parameters
#   Initialise Facebook js SDK
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   Facebook JS Documentation
----------------------------------------------------------------
- NOTE we need to add the login button to the template until we have the access token
<fb:login-button scope="public_profile,email,user_friends" onlogin="checkLoginState();"></fb:login-button>

- Commands -
FB.login(function(response) {}, {scope: 'public_profile,email'});
FB.logout(function(response) {});
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#   Facebook js SDK variables / parameters
--------------------------------------------------------------*/
var appId = '239899259844996'
// Check the latest version from https://developers.facebook.com/docs/apps/changelog/
var sdkVersion = 'v2.9'
var permissions = 'public_profile,email,user_friends'
//var accessToken = ''
var accessToken = 'EAADaLZBYqOYQBAM9at78D3pJWY4jCDt3SLltAsyGFLedYJMmIBh3cQZAtulQQhCo3ULosieHZBtUrEpJI4TONMzywdqA9OoRaZCENvwxTh3zExhzJS4E3Esk9Prj6txgtzdZCuZBJ8KLcTbndGbd4IYON0S5aODrp96JMJiITak79mjNNCD0rR3TeVtwu2cbMZD'
var currentAccessToken = ''
var pageName = 'RedSparrowPizza'

/*--------------------------------------------------------------
#   Initialise Facebook js SDK
--------------------------------------------------------------*/

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
  //console.log('statusChangeCallback');
  //console.log(response);
  // The response object is returned with a status field that lets the
  // app know the current login status of the person.
  // Full docs on the response object can be found in the documentation
  // for FB.getLoginStatus().

  //document.getElementsByClassName('fb_iframe_widget').style['display'] = 'none';


  if (response.status === 'connected') {
    // Logged into your app and Facebook.
    currentAccessToken = response.authResponse.accessToken
    testAPI();
    getFeed();
  } else {
    // The person is not logged into your app or we are unable to tell.
  }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

/* Initialise facebook js SKD */
window.fbAsyncInit = function() {
FB.init({
  appId            : appId,
  autoLogAppEvents : true,
  cookie           : true,  // enable cookies to allow the server to access
                      // the session
  xfbml            : true,  // parse social plugins on this page
  version          : sdkVersion // use graph api version
});

// Now that we've initialized the JavaScript SDK, we call
// FB.getLoginStatus().  This function gets the state of the
// person visiting this page and can return one of three states to
// the callback you provide.  They can be:
//
// 1. Logged into your app ('connected')
// 2. Logged into Facebook, but not your app ('not_authorized')
// 3. Not logged into Facebook and can't tell if they are logged into
//    your app or not.
//
// These three cases are handled in the callback function.

FB.getLoginStatus(function(response) {
  statusChangeCallback(response);

  // hide facebook js sdk iframes
  document.getElementById('fb_xdm_frame_http').style['display'] = 'none';
  document.getElementById('fb_xdm_frame_https').style['display'] = 'none';
});

};

// Load the SDK asynchronously
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
  //console.log('Welcome!  Fetching your information.... ');
  FB.api('/me', function(response) {
    //console.log('Successful login for: ' + response.name);
  });
}

function getFeed() {
  FB.api('/'+pageName+'/feed?limit=5', function(response) { console.log( "Feed: ", response ); })
}
