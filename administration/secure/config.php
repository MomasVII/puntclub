<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_System_Core_Framework
// Version 2.1.3
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// GLOBAL DEFINITIONS //////////////////////////////////////////////////////////////////////////////////////////////////

//find the domain prefix (sub-domain) if it's equal to 'dev' or 'staging' change config params and environment definition
$host = explode('.', $_SERVER['HTTP_HOST']);
$sub_domain = array_shift($host);

if ($sub_domain === 'local') {

    //beanstalks run in UTC time so we need to adjust to AEST
    date_default_timezone_set('Australia/Melbourne');

    //define environment as development
    define('ENVIRONMENT', 'local');

    //define protocol
    define('PROTOCOL', 'http');

    //db definitions connection definition (used in mysqli_db.library.php and others)
    define('DB_HOST', 'aa113izlckze0v1.c2yoe9dwsuu9.ap-southeast-2.rds.amazonaws.com');
    define('DB_USER', 'd8qXdG5dZvcfcDw');
    define('DB_PASS', 'q0vurFWnAp8wCLT');
    define('DB_NAME', 'development');

    //AWS S3 bucket and access key pair (used in upload.library.php and S3.class.php)
    define('S3_BUCKET', 'dyn-postcode.auspost.com.au-v2');
    define('S3_FOLDER', 'dev');
    define('IAM_KEY_ID', 'AKIAI7OBYM7WNN7GT54Q');
    define('IAM_KEY_SECRET', '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');

    //define the end user's ip address
    define('END_USER_IP', $_SERVER['REMOTE_ADDR']);

    //session definitions (used in session.library.php)
    define('SESSION_NAME', 'ViEI0a9Ci58bknV');
    define('SESSION_LIFE', 43200);

    //contact email address
    define('AP_SUPPORT_EMAIL', 'developer@rare.com.au');

} elseif ($sub_domain === 'staging' || $sub_domain === 'stage' || $sub_domain === 'stage-postcode' || $sub_domain === 'stage-postmove') {

    //beanstalks run in UTC time so we need to adjust to AEST
    date_default_timezone_set('Australia/Melbourne');

    //define environment as staging
    define('ENVIRONMENT', 'stage');

    //define protocol
    define('PROTOCOL', 'http');

    //db definitions connection definition (used in mysqli_db.library.php and others)
    define('DB_HOST', 'aa113izlckze0v1.c2yoe9dwsuu9.ap-southeast-2.rds.amazonaws.com');
    define('DB_USER', 'd8qXdG5dZvcfcDw');
    define('DB_PASS', 'q0vurFWnAp8wCLT');
    define('DB_NAME', 'staging');

    //AWS S3 bucket and access key pair (used in upload.library.php and S3.class.php)
    define('S3_BUCKET', 'dyn-postcode.auspost.com.au-v2');
    define('S3_FOLDER', 'stage');
    define('IAM_KEY_ID', 'AKIAI7OBYM7WNN7GT54Q');
    define('IAM_KEY_SECRET', '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');

    //define the end user's ip address
    define('END_USER_IP', $_SERVER['REMOTE_ADDR']);

    //session definitions (used in session.library.php)
    define('SESSION_NAME', '8bF55FDmoQjwKQI');
    define('SESSION_LIFE', 3600);

    //contact email address
    define('AP_SUPPORT_EMAIL', 'apdata.help@auspost.com.au');
} else {

    //beanstalks run in UTC time so we need to adjust to AEST
    date_default_timezone_set('Australia/Melbourne');

    //define environment as live
    define('ENVIRONMENT', 'live');

    //define protocol
    define('PROTOCOL', 'https');

    //db definitions connection definition (used in mysqli_db.library.php and others)
    define('DB_HOST', 'aa113izlckze0v1.c2yoe9dwsuu9.ap-southeast-2.rds.amazonaws.com');
    define('DB_USER', 'd8qXdG5dZvcfcDw');
    define('DB_PASS', 'q0vurFWnAp8wCLT');
    define('DB_NAME', 'live');

    //AWS S3 bucket and access key pair (used in upload.library.php and S3.class.php)
    define('S3_BUCKET', 'dyn-postcode.auspost.com.au-v2');
    define('S3_FOLDER', 'live');
    define('IAM_KEY_ID', 'AKIAI7OBYM7WNN7GT54Q');
    define('IAM_KEY_SECRET', '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');

    //force https using beanstalk method
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
        header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        die();
    }

    //get end user ip using beanstlk method
    define('END_USER_IP', $_SERVER['HTTP_X_FORWARDED_FOR']);

    //session definitions (used in session.library.php)
    define('SESSION_NAME', '0XfTu8n65N59DKK');
    define('SESSION_LIFE', 3600);

    //contact email address
    define('AP_SUPPORT_EMAIL', 'apdata.help@auspost.com.au');
}

//define state array used inside our state dropdowns
$stateArray = array("act"=>"Australian Capital Territory","nsw"=>"New South Wales","nt"=>"Northern Territory","qld"=>"Queensland","sa"=>"South Australia","tas"=>"Tasmania","vic"=>"Victoria","wa"=>"Western Australia");

//boolean switch to turn off errors (used in error_handler.library.php)
define('SILENCE_ERRORS', false); //set to true to stop errors being logged.

//security salt (should be unique to each site)
define('SALT', 'LuWO1r1ZdlRoGdV');

//define the site title
define('TITLE', 'Postcode Data & Movers Statistics Administration');

//set file size limit
define('FILE_SIZE_LIMIT', '30'); //as MB

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LIBRARY AND CLASS LOAD //////////////////////////////////////////////////////////////////////////////////////////////

//filter class that applies CRLF line endings for fputcsv
class crlf_filter extends php_user_filter
{
    function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {

            //make sure the line endings aren't already CRLF
            $bucket->data = preg_replace("/(?<!\r)\n/", "\r\n", $bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

//nullify any pre-existing registered autoloaders for safety
spl_autoload_register(null, false);

//specify legal extensions that may be loaded
spl_autoload_extensions('.php, .library.php, .class.php');

//framework library loader
function library_loader($library)
{
    //attempt to load file and include library
    $file_name = strtolower($library).'.library.php';
    $file = LOCAL.'secure/library/'.$library.'/'.$file_name;

    //make sure the file exists and is readable
    if (file_exists($file) && is_readable($file)) {
        require $file;
    }
}

//site class loader
function class_loader($class)
{
    //attempt to load file and include class
    $file_name = strtolower($class).'.class.php';
    $file = LOCAL.'secure/class/'.$file_name;

    //make sure the file exists and is readable
    if (file_exists($file) && is_readable($file)) {
        require $file;
    }
}

//register the loader functions
spl_autoload_register('library_loader');
spl_autoload_register('class_loader');

//make sure that libraries have been required before attempting to instantiate them
if (isset($required_libraries) && !empty($required_libraries)) {
    foreach ($required_libraries as $rl) {
        //use a variable variable to define the library namespaces
        ${$rl} = new $rl();
    }
}

//make sure that classes have been required before attempting to instantiate them
if (isset($required_classes) && !empty($required_classes)) {
    foreach ($required_classes as $rc) {
        //use a variable variable to define the class namespaces
        ${$rc} = new $rc();
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
