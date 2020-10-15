<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: Rare_Site_Core_Framework
// Version 2.1.3
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '../');

//configure local directory reference (usually blank)
define('LOCAL', '');

//name the framework libraries you need in scope (cross dependencies mean the order matters)
$required_libraries = array(
    //recommended default set
    'error_handler',
    'mysqli_db',
    'validate',
    'session',
    'sentry',
    'password_hash',
    'shortcut',
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
);

//initialize the framework
require(LOCAL . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

$response = '';

//generate core password
//print_r('USER '.$auth->hash_password(SALT.'PASSWORD')['content'].'<br />');

if ($auth->is_signed_in()) {
    header('Location: ' . LOCAL . 'report.html');
    die();
}

//perform login
if (!empty($_POST)) {
    $sign_in = $auth->sign_in($_POST);

    if ($sign_in['boolean']) {
        header('Location: ' . LOCAL . 'report.html');
        die();
    } else {
        $response = 'Error: '.$sign_in['response'];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', LOCAL . 'secure/include/head.include.php');

//foot include
define('FOOT', LOCAL . 'secure/include/foot.include.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Login');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Login');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
            <link rel="stylesheet" type="text/css" href="web/css/foundation.min.css" />
            <link rel="stylesheet" type="text/css" href="web/css/normalize.css" />
            <link rel="stylesheet" type="text/css" href="web/css/typography.css" />
            <link rel="stylesheet" type="text/css" href="web/css/base.css" />
            <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
            <link rel="stylesheet" type="text/css" href="web/css/index.page.css" />
        ');

//define the individual page javascript that runs at the start of the page - delimiter: RETURN
define('HEAD_JS', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: RETURN
define('FOOT_JS', '
            <script defer type="text/javascript" src="web/script/jquery-3.2.1.min.js"></script>
            <script defer type="text/javascript" src="web/script/form_validator.js"></script>
            <script defer type="text/javascript" src="web/script/foundation.min.js"></script>
            <script defer type="text/javascript" src="web/script/what-input.js"></script>
            <script defer type="text/javascript" src="web/script/index.page.js"></script>
        ');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/index.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
