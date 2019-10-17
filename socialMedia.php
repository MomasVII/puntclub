<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: rare_core
// Version 2.1.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '');

//configure local directory reference (usually blank)
define('LOCAL', '');

//name the framework libraries you need in scope (cross dependencies mean the order matters)
$required_libraries = array();

//name the site classes you need in scope
$required_classes = array(
    'socialmedia'
);

//initialize the framework
require(ROOT . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

$response = '';
$instagramFeed = '';
$twitterFeed = '';
$faceboookFeed = '';

/*--------------------------------------------------------------
#   Instagram Feed Interation
--------------------------------------------------------------*/

/*  Instagram Developer credentials
*
*   UN: lucas@rare.com.au
*   PW: vJrX2F!g1189#$9E
*/

/*  Usage
*   Log in, create a new client add in new sites details etc to get the app keys
*/

/* Instagram variables */
$USER_ID = '5539692796';
$ACCESS_TOKEN = '5539692796.b3e60e7.3d157aee2e4f4b6ea93cc179489550a4';
$INITIALCOUNT = 3; /* initial number of images we pull and display from the server on load */
$NEXTCOUNT = 3; /* amount of images we pull when we ajax call to load more */

/* $INITIALCOUNT= Number of images to return NOTE: When value > 0 we generate a pagination value to pull the next count value of images */
if ( $INITIALCOUNT > 0 ) {
    $URL = 'https://api.instagram.com/v1/users/'.$USER_ID.'/media/recent/?access_token='.$ACCESS_TOKEN.'&count='.$INITIALCOUNT;
} else {
    $URL = 'https://api.instagram.com/v1/users/'.$USER_ID.'/media/recent/?access_token='.$ACCESS_TOKEN;
}

/* Get instagram feed */
$instagramFeed = $socialmedia->get_instagram_feed($URL);


/*--------------------------------------------------------------
#   Twitter Feed Interation
--------------------------------------------------------------*/

/*  Twitter Developer credentials
*
*   UN: lucas@rare.com.au
*   PW: ia35H6C@fw#D9%e#
*/

/*  Usage
*   Log in, create a new app add in new sites details etc to get the app keys
*/

/* Twitter variables */
/* Info from: https://www.codeofaninja.com/2015/08/display-twitter-feed-on-website.html */

// keys from your app
$oauth_access_token = '872243301318930432-alSvdcBmBDwPtYWagVvtbnb2WW8S7Ek';
$oauth_access_token_secret = 'LELVHANMzajQ1I3RhZoFMui37mNJyojiKIrwPehwEB1xc';
$consumer_key = '5UAyLnWlfB39tpDOgx0TACI4L';
$consumer_secret = '4dc6IylQslw4mUsxdqA9JbItyyQCKUtLlVBT6KoTujFrlkxXKn';
$tweetCount = 5;
$screen_name = 'redsparrowpizza'; /* NOTE: Update account we load the feed of here*/
// HASH_TAG
// LOCATION
// AJAX REQUEST NEXT 5

$twitterFeed = $socialmedia->get_twitter_feed( $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret, $tweetCount, $screen_name );


/*--------------------------------------------------------------
#   Facebook Feed Integration
--------------------------------------------------------------*/

/*  Usage
*   Log in at developers.facebook.com, create a new app add in new sites details etc to get the app keys
*   NOTE: in settings Advanced you might need to turn 'Require App Secret' off.
*/

/*
 * Configuration and setup Facebook SDK
 */
$appId         = '239899259844996';
$appSecret     = '10e5574f2a7dc70e29acb4b41c14c1e9';
$clientToken = '13d75bc49030513fcd8191c01e5d3439';
$redirectURL   = 'http://'.ROOT.'/socialMedia.html';
$fbPermissions = ['email', 'public_profile', 'user_friends']; // Optional permissions
// Check the latest version from https://developers.facebook.com/docs/apps/changelog/
$sdkVersion = 'v2.9';
$sessionAccessToken = 'EAADaLZBYqOYQBAJiTopEfOgtAbGXe5OFaWlZBP5U9haJsgo88joSWde4NsgOZCekU9uxiotK2B0A0ZBEcTZCFiNkfdTiC9EdDzi1cr1BEU03T4eZCYUEQWlZActMTG0fJl3UpyWFUZCuLDJKFgMltBmlNuRyb8JyRI92YFP21TGnLAZDZD';
//$sessionAccessToken = '';
$page_id            = '1601458493503498';
$page_name    = 'RedSparrowPizza';
$limit = 10;

if(!session_id()){
    session_start();
}

// Include Facebook php SDK autoloader
require_once __DIR__ .'/secure/side_load/facebook-php-sdk-v5/autoload.php';

// Initialize the Facebook PHP SDK v5.
$fb = new Facebook\Facebook([
  'app_id'                => $appId,
  'app_secret'            => $appSecret,
  'default_graph_version' => $sdkVersion,
]);

/* IF We are currently not logged in / dont have an access token for the site  */
if( $sessionAccessToken == '' ){
  $response = $socialmedia->facebook_login( $fb, $redirectURL, $fbPermissions );
} else {
  /* We have access token -> get facebook feed */
  $faceboookFeed = $socialmedia->facebook_getFeed( $fb, $sessionAccessToken, $page_id, $limit );
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', ROOT . 'secure/include/head.include.php');

//foot include
define('FOOT', ROOT . 'secure/include/foot.include.php');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Social Media');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Social Media Feed Page');

//define the individual page styles - delimiter: COMMA
define('STYLES', '
    '.ROOT. 'web/style/foundation.min.css,
    '.ROOT. 'web/style/typography.css,
    '.ROOT. 'web/style/base.css,
    '.ROOT. 'web/style/header.css,
    '.ROOT. 'web/style/footer.css,
    '.ROOT. 'web/style/navigation.css,

    '.ROOT. 'web/style/instagram.css,
    '.ROOT. 'web/style/overview.css
');

//define the individual page javascript that runs at the start of the page - delimiter: COMMA
define('HEAD_JS', '');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: COMMA
define('FOOT_JS', '

    '.ROOT.'web/script/jquery-3.2.1.min.js,
    '.ROOT.'web/script/loadsh.js,
    '.ROOT.'web/script/foundation.min.js,
    '.ROOT.'web/script/tweenmax.min.js,
    '.ROOT.'web/script/resizehandler.js,
    '.ROOT.'web/script/navigation.js,
    '.ROOT.'web/script/gaTrack.js,
    '.ROOT.'web/script/svg.js,
    '.ROOT.'web/script/init.js,
    '.ROOT.'web/script/socialMedia.page.js,
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/socialMedia.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
