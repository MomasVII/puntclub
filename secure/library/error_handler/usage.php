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
 * Gotchya: This library runs an independent connection to the database via MySQLi. This is because there is a high
 * chance that the mysqli library will have already been destroyed due to error.
 *
 * Note: This library uses a trick to catch fatal errors which isn't approved by PHP and is argued by some as bad
 * practice. We've decided to do this to keep all error behaviour consistent and be absolutely sure that no PHP errors
 * will ever announce themselves on a live site (potentially being a security risk.)
 *
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//CONTROL //////////////////////////////////////////////////////////////////////////////////////////////////////////////

//set container variable for usage returns
$print = '';

////////// START: how to instantiate error library

    //db definitions need to be included because the session library has it's own db thread.
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'QrPco2vSPk');
    define('DB_NAME', 'framework_test');

    //silence errors 
    define('SILENCE_ERRORS', false); //set to true to stop errors being logged.

    //instantiate error
    require_once(LOCAL.'error_handler.library.php');
    $error_handler = new error_handler();

////////// END: how to instantiate error library

////////// START: how to set a session variable

    trigger_error('TEST ERROR.', E_USER_ERROR);
    $print .= '<p>Error set.</p>';

////////// END: how to set a session variable

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('TITLE', 'Rare Framework');
define('PAGE', 'Usage for: error_handler.library.php');
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