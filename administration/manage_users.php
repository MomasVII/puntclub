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
    'shortcut',
    'paginate'
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
    'admin_user',
);

//initialize the framework
require(LOCAL . 'secure/config.php');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

$response = '';
$users_print = '';
$pagination_print = '';

//set paginate params container to add search filters to
$paginate_params = array();

//check that the end-user is admin
if (!isset($_SESSION['access']) || empty($_SESSION['access']) || $_SESSION['access'] !== 'admin') {

    //destroy and reset the session
    session_destroy();

    //send back to login
    header('Location: '.LOCAL.'index.html');
    die();
}

//get subscribers with filter if set
$all_users = $admin_user->get_all();
if ($all_users['boolean']) {

    //create users table view
    foreach ($all_users['content'] as $user_data) {
        if ($user_data['disabled']) {
            $user_data['access'] = 'Disabled';
        }

        $users_print .='
            <a href="'.LOCAL.'user_update.html?id='.$user_data['user_id'].'" class="flex-row" title="Edit User">
                <div class="item">'.date('d/m/Y', $user_data['insert_time']).'</div>
                <div class="item">'.$user_data['first_name'].' '.$user_data['last_name'].'</div>
                <div class="item">'.$user_data['email'].'</div>
                <div class="item">'.$validate->camel_case($user_data['access']).'</div>
            </a>
        ';
    }
} else {
    $response = 'Error: '.$all_users['response'];
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
define('PAGE', 'Manage Users');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Manage Users');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/base.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
');
//define the individual page javascript that runs at the start of the page - delimiter: RETURN
define('HEAD_JS', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: RETURN
define('FOOT_JS', '
    <script defer type="text/javascript" src="'.LOCAL.'web/script/jquery-3.2.1.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/form_validator.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/foundation.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/what-input.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/manage_users.page.js"></script>
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/manage_users.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
