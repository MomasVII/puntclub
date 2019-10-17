<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_System_Core_Framework
// Version 2.1.3
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// GLOBAL DEFINITIONS //////////////////////////////////////////////////////////////////////////////////////////////////

$host = explode('.', $_SERVER['HTTP_HOST']);
$sub_domain = array_shift($host);

//define runtime environment
if ($sub_domain == 'local' || $sub_domain == 'sandbox') {

    //define environment as development
    define('ENVIRONMENT', 'local');

    //define protocol
    define('PROTOCOL', 'http');

    //db definition
    define('DB_HOST', 'YOUR_DB_HOST_HERE');
    define('DB_USER', 'YOUR_DB_USER_HERE');
    define('DB_PASS', 'YOUR_DB_PASSWORD_HERE');
    define('DB_NAME', 'development'); //local and dev share some storage and db endpoints

    //define the end user's ip address
    define('END_USER_IP', $_SERVER['REMOTE_ADDR']);

} elseif ($sub_domain == 'dev') {

    //define environment as development
    define('ENVIRONMENT', 'dev');

    //define protocol
    define('PROTOCOL', 'http');

    //db definition
    define('DB_HOST', 'YOUR_DB_HOST_HERE');
    define('DB_USER', 'YOUR_DB_USER_HERE');
    define('DB_PASS', 'YOUR_DB_PASSWORD_HERE');
    define('DB_NAME', 'development'); //local and dev share some storage and db endpoints

    //define the end user's ip address
    define('END_USER_IP', $_SERVER['REMOTE_ADDR']);

} elseif ($sub_domain == 'stage') {

    //define environment as staging
    define('ENVIRONMENT', 'stage');

    //define protocol
    define('PROTOCOL', 'http');

    //db definition
    define('DB_HOST', 'YOUR_DB_HOST_HERE');
    define('DB_USER', 'YOUR_DB_USER_HERE');
    define('DB_PASS', 'YOUR_DB_PASSWORD_HERE');
    define('DB_NAME', 'staging');

    //define the end user's ip address
    define('END_USER_IP', $_SERVER['REMOTE_ADDR']);

} else {

    //define environment as live
    define('ENVIRONMENT', 'live');

    //define protocol
    define('PROTOCOL', 'https');

    //force https using beanstalk method
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit();
    }

    //get end user ip using beanstlk method
    define('END_USER_IP', $_SERVER['HTTP_X_FORWARDED_FOR']);

    //db definition
    define('DB_HOST', 'YOUR_DB_HOST_HERE');
    define('DB_USER', 'YOUR_DB_USER_HERE');
    define('DB_PASS', 'YOUR_DB_PASSWORD_HERE');
    define('DB_NAME', 'live');
}

//google analytics tracking code
define('GA_TRACK_ID', 'UA-669331-21');

//site author
define('AUTHOR', 'raremedia pty ltd');

//switch to make the front-end web assets compiler to turn on - DO NOT commit this to any remote repo as 'true'
define('FORCE_COMPILE', false);

//set locale to Australia
setlocale(LC_MONETARY, 'en_AU');

//set timezone to australia
date_default_timezone_set('Australia/Melbourne');

//boolean switch to turn off errors (used in error_handler.library.php)
define('SILENCE_ERRORS', false); //set to true to stop errors being logged.

//session definitions (must be unique to each site)
define('SESSION_NAME', 'YOUR_RANDOM_STRING_HERE');
define('SESSION_LIFE', 2700);

//security salt (must be unique to each site)
define('SALT', 'YOUR_RANDOM_STRING_HERE');

//define the site title
define('TITLE', 'YOUR_SITE_TITLE_HERE');

//START CONTENT GLOBALS

    define('CONTACT_EMAIL', 'info@rare.com.au');
    define('PHONE', '94289558');
    define('COMPANY_NAME', 'Rare Media');
    define('ADDRESS', '30 Cubitt Street');
    define('SUBURB', 'Cremorne');
    define('STATE', 'Vic');
    define('POSTCODE', '3121');
    define('COUNTRY', 'Australia');
    define('COPYRIGHT', '&copy; '.COMPANY_NAME.' '.date('Y'));

//END CONTENT GLOBALS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LIBRARY AND CLASS LOAD //////////////////////////////////////////////////////////////////////////////////////////////

//define class and library containers if the controller hasn't declared them
if (!isset($required_libraries)) {
    $required_libraries = array();
}
if (!isset($required_classes)) {
    $required_classes = array();
}

//name the framework libraries you need in GLOBAL scope (cross dependencies mean the order matters)
//$required_libraries[] = 'error_handler';//TODO: enable me once you've got a database
//$required_libraries[] = 'mysqli_db';//TODO: enable me once you've got a database
//$required_libraries[] = 'session';//TODO: enable me once you've got a database
$required_libraries[] = 'shortcut';
$required_libraries[] = 'validate';
$required_libraries[] = 'web_compile';

//name the site classes you need in GLOBAL scope
$required_classes[] = 'navigation';

//nullify any pre-existing registered autoloaders for safety
spl_autoload_register(null, false);

//specify legal extensions that may be loaded
spl_autoload_extensions('.php, .library.php, .class.php');

//framework library loader
function library_loader($library)
{
    //attempt to load file and include library
    $file_name = strtolower($library) . '.library.php';
    $file = ROOT . 'secure/library/' . $library . '/' . $file_name;

    //make sure the file exists and is readable
    if (file_exists($file) && is_readable($file)) {
        require $file;
    }
}

//site class loader
function class_loader($class)
{
    //attempt to load file and include class
    $file_name = strtolower($class) . '.class.php';
    $file = ROOT . 'secure/class/' . $file_name;

    //make sure the file exists and is readable
    if (file_exists($file) && is_readable($file)) {
        require $file;
    }
}

//register the loader methods
spl_autoload_register('library_loader');
spl_autoload_register('class_loader');

//make sure that libraries have been required before attempting to instantiate them
if (isset($required_libraries) && !empty($required_libraries)) {
    foreach ($required_libraries as $rl) {
        //skip load if the library is already instantiated
        if (!class_exists((string)$rl, false)) {

            //use a variable variable to define the library namespaces
            ${$rl} = new $rl();
        }
    }
}

//make sure that classes have been required before attempting to instantiate them
if (isset($required_classes) && !empty($required_classes)) {
    foreach ($required_classes as $rc) {

        //skip load if the class is already instantiated
        if (!class_exists((string)$rc, false)) {

            //use a variable variable to define the class namespaces
            ${$rc} = new $rc();
        }
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
