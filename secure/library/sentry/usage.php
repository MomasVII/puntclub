<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_Framework_Testing_Controller
// Version 0.0.4
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//SETTINGS /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//configure relative location to root directory eg: '../' or ''
define('ROOT', '../');
define('LOCAL', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//NOTES, COMMENTS AND GOTCHYAS /////////////////////////////////////////////////////////////////////////////////////////
/*
 * Gotchya: This library relies on the MySQLi library and the Validate library.
 *
 * Gotchya: If an end user doesn't allow cookies this library won't function properly, you can choose on a case by case
 * basis how to handle this situation, a method is provided to tell you if the end user is being tracked properly or
 * not.
 *
 * Note: This library is not to be used for marketing analytics, data generated from this library is for security purposes.
 *
 * Note: If cookies aren't allowed, this library will still function, it just won't be able to detect return visitors.
 *
 * Note: Most of this library' work is done in the constructor.
 *
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//CONTROL //////////////////////////////////////////////////////////////////////////////////////////////////////////////

//turn on all errors (an error log can also be found in the framework root directory)
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(2047);

//set container variable for usage returns
$print = '';

////////// START: how to instantiate sentry library

    //generally you should be starting a session before instantiating libraries anyway
    //session_start(); //sentry will start a session if you forget

    //validate library is a dependency for this library
    require_once(ROOT.'validate/validate.library.php');
    $validate = new validate();

    //mysqli library is a dependency for this library
    define('library', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'QrPco2vSPk');
    define('DB_NAME', 'framework_test');
    require_once(ROOT.'mysqli_db/mysqli_db.library.php');
    $mysqli_db = new mysqli_db();

    //session library
    define('SESSION_NAME', 'framework_test');
    define('SESSION_LIFE', 2700);
    require_once(ROOT.'session/session.library.php');
    $session = new session();

    //instantiate sentry, the constructor will grab and log every bit off information it can immediately
    require_once(LOCAL.'sentry.library.php');
    $sentry = new sentry();

////////// END: how to instantiate sentry library

////////// START: how to get the URL as a element array

//get the current URL as an array broken into each element
$url_array = $sentry->get_complete_url(true); //true for array, false for string

if(!empty($url_array)){
    $print .= '<p>URL array get succeeded.</p>';
    //print_r($url_array); //uncomment to see raw data output
}else{
    $print .= '<p>URL array get failed.</p>';
}

////////// END: how to get the URL as a element array

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: sentry.library.php');
define('CHARSET', 'text/html; charset=utf-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo TITLE.' - '.PAGE; ?></title>
        <meta http-equiv="content-type" content="<?php echo CHARSET; ?>" />
    </head>
    <body>
        <h1><?php echo TITLE; ?></h1>

        <h2><?php echo PAGE; ?></h2>

        <p>If you're not sure what this page is for, please open the <a href="<?php echo LOCAL; ?>__README.txt" title="README FILE">README</a> or speak to Gordon MacK</p>

        <hr />

        <h2>Usage Results</h2>

        <?php if(isset($print) && !empty($print)){echo $print;} ?>
    </body>
</html>