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
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
    'subscriber',
    'product',
);

//initialize the framework
require(LOCAL.'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
*   NOTE: $response needs to begin with success:, error: or alert: to display on the frontend correctly
*   the the error type prefix (i.e. error: ) doesn't display on the front end.
*/
$response = '';
$state_print = '';

/* Set options for state dropdown */
if (isset($stateArray) && !empty($stateArray)) {
	$state_print .='<option value="">Please select</option>';
    foreach ($stateArray as $key => $value) {
        $state_print .='<option value="'.$key.'">'.$value.'</option>';
    }
}

//listen for insert post
if (isset($_POST) && !empty($_POST)) {

    //attempt to update the subscriber
    $insert = $subscriber->insert();
    if($insert['boolean']){
        $response = 'Success: '.$insert['response'];
    }else{
        $response = 'Error: '.$insert['response'];
    }
}

//set product print containers
$product_select_print = '<option value="" selected >Product\'s couldn\'t be found</option>';
$subscription_term_print = array();
$national_price_print = array();
$state_price_print = array();
$postcode_price_print = array();

//get all products
$product_select = $product->get_all();
if ($product_select['boolean']) {

    $product_select_print = '<option value="" selected>Select Product</option>';

    //loop through each product
    foreach($product_select['content'] as $s){

        //create product select options
        $product_select_print .= '<option value="'.$s['product_id'].'">'.$s['name'].' ('.$s['sku'].')</option>';

        //collate the subscription term of each product
        $subscription_term_print[$s['product_id']] = $s['subscription_term'];

        //set postcode pricing matrix
        $national_price_matrix = array();
        $national_price_matrix['filter_enabled'] = $s['national_filter_enabled'];
        if($national_price_matrix['filter_enabled'] === 1){
            $national_price_matrix['onceoff_enabled'] = $s['national_onceoff_enabled'];
            if($s['national_onceoff_enabled'] === 1 && !empty($s['national_onceoff_price'])){
                $national_price_matrix['onceoff_price'] = $s['national_onceoff_price'];
            }
            $national_price_matrix['quarterly_enabled'] = $s['national_quarterly_enabled'];
            if($s['national_quarterly_enabled'] === 1 && !empty($s['national_quarterly_price'])){
                $national_price_matrix['quarterly_price'] = $s['national_quarterly_price'];
            }
            $national_price_matrix['monthly_enabled'] = $s['national_monthly_enabled'];
            if($s['national_quarterly_enabled'] === 1 && !empty($s['national_quarterly_price'])){
                $national_price_matrix['monthly_price'] = $s['national_monthly_price'];
            }
        }
        $national_price_print[$s['product_id']] = $national_price_matrix;

        //set state pricing matrix
        $state_price_matrix = array();
        $state_price_matrix['filter_enabled'] = $s['state_filter_enabled'];
        if($state_price_matrix['filter_enabled'] === 1){
            $state_price_matrix['onceoff_enabled'] = $s['state_onceoff_enabled'];
            if($s['state_onceoff_enabled'] === 1 && !empty($s['state_onceoff_price'])){
                $state_price_matrix['onceoff_price'] = (array)json_decode($s['state_onceoff_price']);
            }
            $state_price_matrix['quarterly_enabled'] = $s['state_quarterly_enabled'];
            if($s['state_quarterly_enabled'] === 1 && !empty($s['state_quarterly_price'])){
                $state_price_matrix['quarterly_price'] = (array)json_decode($s['state_quarterly_price']);
            }
            $state_price_matrix['monthly_enabled'] = $s['state_monthly_enabled'];
            if($s['state_monthly_enabled'] === 1 && !empty($s['state_monthly_price'])){
                $state_price_matrix['monthly_price'] = (array)json_decode($s['state_monthly_price']);
            }
        }
        $state_price_print[$s['product_id']] = $state_price_matrix;

        //set postcode pricing matrix
        $postcode_price_matrix = array();
        $postcode_price_matrix['filter_enabled'] = $s['postcode_filter_enabled'];
        if($postcode_price_matrix['filter_enabled'] === 1){
            $postcode_price_matrix['onceoff_enabled'] = $s['postcode_onceoff_enabled'];
            if($s['postcode_onceoff_enabled'] === 1 && !empty($s['postcode_onceoff_price'])){
                $postcode_price_matrix['onceoff_price'] = $s['postcode_onceoff_price'];
            }
            $postcode_price_matrix['quarterly_enabled'] = $s['postcode_quarterly_enabled'];
            if($s['postcode_quarterly_enabled'] === 1 && !empty($s['postcode_quarterly_price'])){
                $postcode_price_matrix['quarterly_price'] = $s['postcode_quarterly_price'];
            }
            $postcode_price_matrix['monthly_enabled'] = $s['postcode_monthly_enabled'];
            if($s['postcode_monthly_enabled'] === 1 && !empty($s['postcode_monthly_price'])){
                $postcode_price_matrix['monthly_price'] = $s['postcode_monthly_price'];
            }
        }
        $postcode_price_print[$s['product_id']] = $postcode_price_matrix;
    }
} else {
    $response = 'Error: '.$product_select['response'];
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', LOCAL.'secure/include/head.include.php');

//foot include
define('FOOT', LOCAL.'secure/include/foot.include.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Add Subscriber');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Add Subscriber');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/flatpickr.min.css" />
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
    <script type="text/javascript">
        var subscription_term = '.json_encode($subscription_term_print).';
        var national_price_matrix = '.json_encode($national_price_print).';
        var state_price_matrix = '.json_encode($state_price_print).';
        var postcode_price_matrix = '.json_encode($postcode_price_print).';
    </script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/jquery-3.2.1.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/form_validator.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/foundation.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/what-input.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/flatpickr.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/tag-box.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/subscriber_insert.page.js"></script>
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL . 'secure/page/subscriber_insert.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
