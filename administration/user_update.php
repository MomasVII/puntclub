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
    'upload',
    'password_hash',
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

/*
*   NOTE: $response needs to begin with success:, error: or alert: to display on the frontend correctly
*   the the error type prefix (i.e. error: ) doesn't display on the front end.
*/
$response = '';

//check that the end-user is admin
if (!isset($_SESSION['access']) || empty($_SESSION['access']) || $_SESSION['access'] !== 'admin') {

    //destroy and reset the session
    session_destroy();

    //send back to login
    header('Location: '.LOCAL.'index.html');
    die();
}

//make sure a user id is set
if(!empty($_GET['id'])) {

    //if post data is set
    if(isset($_POST) && !empty($_POST)){

        //if we're resetting a password
        if(isset($_POST['action']) && $_POST['action'] === 'reset_password'){

            //reset user's password
            $password_reset = $admin_user->reset_password($_GET['id']);
            if($password_reset['boolean']){
                $response = 'Success: '.$password_reset['response'];
            }else{
                $response = 'Error: '.$password_reset['response'];
            }
        }

        //if we're updating the user record
        if(isset($_POST['action']) && $_POST['action'] === 'update'){

            //attempt to update the user data
            $update = $admin_user->update($_GET['id']);
            if($update['boolean']){
                $response = 'Success: '.$update['response'];
            }else{
                $response = 'Error: '.$update['response'];
            }
        }
    }

    //get user record
    $user_data = $admin_user->get_by_id($_GET['id']);
    if (empty($user_data)) {
        $response = 'Error: User couldn\'t be found, please try again';
    }
}else{
    //send back to user list
    header('Location: '.LOCAL.'mangage_users.html');
    die();
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
define('PAGE', 'Update User');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Update User');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/base.css" />
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
require(LOCAL . 'secure/page/user_update.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
