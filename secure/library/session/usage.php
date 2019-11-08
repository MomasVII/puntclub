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
 * Gotchya: This library runs an independent connection to the database via MySQLi. This is because the session handler
 * functions are called after all other code is finished executing which means the MySQLi library we usually use has
 * already destroyed itself. The result is that we still need the database definitions but we don't need the MySQLi
 * library or the validate library.
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

////////// START: how to instantiate session library

    //db definitions need to be included because the session library has it's own db thread.
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'QrPco2vSPk');
    define('DB_NAME', 'framework_test');

    //session definitions
    define('SESSION_NAME', 'framework_test'); //name of session, visible on client side in cookie, never put a '.' character in the session name.
    define('SESSION_LIFE', 2700); //dormant lifetime of session in seconds.

    //instantiate session
    require_once(LOCAL.'session.library.php');
    $session = new session();

////////// END: how to instantiate session library

////////// START: how to set a session variable

    $_SESSION['TEST'] = 'MY6jMK6kDiYPnhyr';
    $print .= '<p>Set variable set.</p>';
    //print_r($_SESSION); //uncomment to see raw data output

////////// END: how to set a session variable

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: session.library.php');
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