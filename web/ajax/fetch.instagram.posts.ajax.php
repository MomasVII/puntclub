<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_Site_core_boilerplate
// Version 2.1.4
// Author: Lucas Jordan
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '../../');

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

//set response var
$response = '';

// get next instagram posts
if( isset(  $_POST['link'] ) ) {
    $URL =  $_POST['link'];
    $response = $socialmedia->get_instagram_feed($URL);

    print_r( json_encode( $response ) );
} else {
    echo $response;
}

?>
