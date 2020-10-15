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
    'shortcut'
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
    'product',
    'subscriber',
    'report',
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

//get heads up metrics
$metrics = $report->get_metrics();

if (isset($_GET['current_subscribers']) && $_GET['current_subscribers'] === '1') {
    $get_subscribers = $report->get_subscribers_csv('current');
    if (!$get_subscribers['boolean']) {
        $response = $get_subscribers['response'];
    }
}

if (isset($_GET['expired_subscribers']) && $_GET['expired_subscribers'] === '1') {
    $get_subscribers = $report->get_subscribers_csv('expired');
    if (!$get_subscribers['boolean']) {
        $response = $get_subscribers['response'];
    }
}

if (isset($_GET['free_download_data']) && $_GET['free_download_data'] === '1') {
    $get_download_data = $report->get_free_download_data_csv();
    if (!$get_download_data['boolean']) {
        $response = $get_download_data['response'];
    }
}

if (isset($_GET['paid_download_data']) && $_GET['paid_download_data'] === '1') {
    $get_download_data = $report->get_paid_download_data_csv();
    if (!$get_download_data['boolean']) {
        $response = $get_download_data['response'];
    }
}
if (isset($_GET['email_log']) && in_array($_GET['email_log'], array('bounce', 'complaint'))) {
    $get_download_data = $report->get_email_log($_GET['email_log']);
    if (!$get_download_data['boolean']) {
        $response = $get_download_data['response'];
    }
}

$action = '';
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
} elseif (!empty($_POST['action'])) {
    $action = $_POST['action'];
}

function download_marketing()
{
    global $report, $response;

    $get_marketing_data = $report->get_marketing_data();
    if (!$get_marketing_data['boolean']) {
        $response = 'Error: '.$get_marketing_data['response'];
    }
}

function upload_marketing()
{
    global $validate, $response, $mysqli_db;

    if (empty($_FILES)) {
        $response = 'Error: Marketing file could not be found, please try again.';
        return;
    }
    if (!in_array($_FILES['marketing_template_file']['type'], array('application/vnd.ms-excel','application/csv'))) {
        $response = 'Error: Marketing file wasn\'t a csv, please select a csv file and try again.';
        return;
    }
    if (($handle = fopen($_FILES['marketing_template_file']['tmp_name'], "r")) === false) {
        $response = 'Error: Marketing file wasn\'t a csv, please select a csv file and try again.';
        return;
    }

    $headers = array();
    $id_index = 0;//index of the id in the csv
    $marketing_opt_in_index = 0;//index of the opt in the csv
    $queries = array();//contains a fraction of the query that will be later join into one

    //build single update sql
    while (($subscriber_data = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($headers)) {
            $headers = $subscriber_data;
            $id_index = array_search('id', $subscriber_data);
            if ($id_index === false) {
                $response = 'Error: Missing id column, please select a csv file and try again.';
                return;
            }
            $marketing_opt_in_index = array_search('marketing_opt_in', $subscriber_data);
            if ($marketing_opt_in_index === false) {
                $response = 'Error: Missing marketing_opt_in column, please select a csv file and try again.';
                return;
            }
            continue;//skip the header row
        }
        //skip if marketing column has an invalid value
        if (!in_array($subscriber_data[$marketing_opt_in_index], array(0,1))) {
            continue;
        }
        //skip if id column has an invalid value
        if (!$validate->sanitise_regex($subscriber_data[$id_index], '/\*?(\d|[A-Z])+/')) {
            continue;
        }
        //translate TRUE/FALSE to 1/0
        if (in_array(strtolower($subscriber_data[$marketing_opt_in_index]), array(1,'true'))) {
            $subscriber_data[$marketing_opt_in_index] = 1;
        } else {
            $subscriber_data[$marketing_opt_in_index] = 0;
        }
        $queries[] = 'SELECT "'.$subscriber_data[$id_index].'" subscriber_id, "'. $subscriber_data[$marketing_opt_in_index].'" marketing_opt_in';
    }
    if (empty($queries)) {
        $response = 'Error: Missing marketing data, please select a csv file and try again.';
        return;
    }
    //build the final sql
    $sql = 'UPDATE subscriber s  INNER JOIN ('.implode(' UNION ', $queries).') t ON (  password( concat("'.SALT.'",s.subscriber_id)) = t.subscriber_id  ) SET  s.marketing_opt_in = t.marketing_opt_in  ';
    $mysqli_db->raw_query($sql);
    $response = 'Success: Your subscriber marketing data has been updated.';
}

if ($action === 'download_marketing') {
    download_marketing();
} elseif ($action === 'upload_marketing') {
    upload_marketing();
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
define('PAGE', 'Reports');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Reports');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
            <link rel="stylesheet" type="text/css" href="web/css/foundation.min.css" />
            <link rel="stylesheet" type="text/css" href="web/css/normalize.css" />
            <link rel="stylesheet" type="text/css" href="web/css/typography.css" />
            <link rel="stylesheet" type="text/css" href="web/css/base.css" />
            <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
            <link rel="stylesheet" type="text/css" href="web/css/report.page.css" />
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
            <script defer type="text/javascript" src="web/script/report.page.js"></script>
        ');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/report.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
