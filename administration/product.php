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
    'paginate',
);

//name the site classes you need in scope
$required_classes = array(
    'auth',
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
$record_print = '';
$pagination_print = '';

//get all products
$all_products = $product->get_all();

//if products were found start pagination and print
if ($all_products['boolean']) {

    //set default pagination page increment and reset if page get var is set and valid
    $page_increment = 1;
    if (isset($_GET['p']) && $_GET['p'] > 0) {
        $page_increment = $_GET['p'];
    }

    //set pagination params
    $paginate->set($all_products['content'], $page_increment);

    //allow first and last buttons
    $paginate->show_first_and_last(true);

    //build pagination print
    $pagination_print = $paginate->build_navigation();

    //get pagination params back (including filtered result set)
    $pagination_parameters = $paginate->return_parameters();

    //check that the filtered record set isn't empty
    if (!empty($pagination_parameters['content'])) {

        //loop out the records
        foreach ($pagination_parameters['content'] as $c) {

            $sku_print = '';
            if (!empty($c['sku'])) {
                $sku_print = ' ('.$c['sku'].')';
            }

            $national_onceoff_price = 'Disabled';
            $national_quarterly_price = 'Disabled';
            $national_monthly_price = 'Disabled';
            if($c['national_filter_enabled'] !== 0){
                if($c['national_onceoff_enabled'] !== 0 && !empty($c['national_onceoff_price'])){
                    $national_onceoff_price = '$'.number_format($c['national_onceoff_price'], 2);
                }
                if($c['national_quarterly_enabled'] !== 0 && !empty($c['national_quarterly_price'])){
                    $national_quarterly_price = '$'.number_format($c['national_quarterly_price'], 2);
                }
                if($c['national_monthly_enabled'] !== 0 && !empty($c['national_monthly_price'])){
                    $national_monthly_price = '$'.number_format($c['national_monthly_price'], 2);
                }
            }

            $state_onceoff_price = 'Disabled';
            $state_quarterly_price = 'Disabled';
            $state_monthly_price = 'Disabled';
            if($c['state_filter_enabled'] !== 0){
                if($c['state_onceoff_enabled'] !== 0){

                    if(!empty($c['state_onceoff_price'])){
                        $c['state_onceoff_price'] = array_diff(array_values((array)json_decode($c['state_onceoff_price'])), array('0.00'));
                        if(!empty($c['state_onceoff_price'])){$state_onceoff_price = '$'.number_format(min($c['state_onceoff_price']), 2).' - $'.number_format(max($c['state_onceoff_price']), 2);}
                    }
                }
                if($c['state_quarterly_enabled'] !== 0){

                    if(!empty($c['state_quarterly_price'])){
                        $c['state_quarterly_price'] = array_diff(array_values((array)json_decode($c['state_quarterly_price'])), array('0.00'));
                        if(!empty($c['state_quarterly_price'])){$state_quarterly_price = '$'.number_format(min($c['state_quarterly_price']), 2).' - $'.number_format(max($c['state_quarterly_price']), 2);}
                    }
                }
                if($c['state_monthly_enabled'] !== 0){
                    if(!empty($c['state_monthly_price'])){
                        $c['state_monthly_price'] = array_diff(array_values((array)json_decode($c['state_monthly_price'])), array('0.00'));
                        if(!empty($c['state_monthly_price'])){$state_monthly_price = '$'.number_format(min($c['state_monthly_price']), 2).' - $'.number_format(max($c['state_monthly_price']), 2);}
                    }
                }
            }

            $postcode_onceoff_price = 'Disabled';
            $postcode_quarterly_price = 'Disabled';
            $postcode_monthly_price = 'Disabled';
            if($c['postcode_filter_enabled'] !== 0){
                if($c['postcode_onceoff_enabled'] !== 0){
                    $postcode_onceoff_price = '$'.number_format($c['postcode_onceoff_price'], 2);
                }
                if($c['postcode_quarterly_enabled'] !== 0){
                    $postcode_quarterly_price = '$'.number_format($c['postcode_quarterly_price'], 2);
                }
                if($c['postcode_monthly_enabled'] !== 0){
                    $postcode_monthly_price = '$'.number_format($c['postcode_monthly_price'], 2);
                }
            }

            //count current national subscribers
            $query_string = '
                SELECT
                    `product_delivery_interval`, `product_data_filter`
                FROM
                    `subscriber`
                WHERE
                    `product` = '.$c['product_id'].' AND
                    `paid_time` > 0 AND
                    `expiry_time` > '.time().' AND
                    `manually_terminated` = 0
            ';
            $subscriber_select = $mysqli_db->raw_query($query_string, false);
            $subscriber_count = array();
            $subscriber_count['national']['total'] = 0;
            $subscriber_count['national']['monthly'] = 0;
            $subscriber_count['national']['quarterly'] = 0;
            $subscriber_count['national']['onceoff'] = 0;
            $subscriber_count['state']['total'] = 0;
            $subscriber_count['state']['monthly'] = 0;
            $subscriber_count['state']['quarterly'] = 0;
            $subscriber_count['state']['onceoff'] = 0;
            $subscriber_count['postcode']['total'] = 0;
            $subscriber_count['postcode']['monthly'] = 0;
            $subscriber_count['postcode']['quarterly'] = 0;
            $subscriber_count['postcode']['onceoff'] = 0;
            foreach($subscriber_select as $s){
                $subscriber_count[$s['product_data_filter']][$s['product_delivery_interval']]++;
                $subscriber_count[$s['product_data_filter']]['total']++;
            }
            $subscriber_count['total'] = $subscriber_count['national']['total'] + $subscriber_count['state']['total'] + $subscriber_count['postcode']['total'];

            $record_print .= '
                <a href="'.LOCAL.'product_update.html?id='.$c['product_id'].'" class="row content-block flex" title="Click to edit '.$c['name'].$sku_print.' product">

                    <div class="item span-three head">Product Name</div>
                    <div class="item span-two head">Subscription Term</div>
                    <div class="item span-two head">Total subscribers</div>

                    <div class="item button btn-gray span-three">'.$c['name'].$sku_print.'</div>
                    <div class="item span-two rounded">'.$c['subscription_term'].' Months</div>
                    <div class="item span-two rounded">'.$subscriber_count['total'].'</div>

                    <div class="item"></div>
                    <div class="item sub-head"><span>Once off<br />cost</span></div>
                    <div class="item sub-head"><span>Quarterly update<br />cost</span></div>
                    <div class="item sub-head"><span>Monthly update cost</span></div>
                    <div class="item sub-head"><span>Once off subscriber</span></div>
                    <div class="item sub-head">Quarterly update subscriber</div>
                    <div class="item sub-head">Monthly update subscriber</div>

                    <div class="item sub-head-2">National</div>
                    <div class="item blue-light-bg">'.$national_onceoff_price.'</div>
                    <div class="item blue-light-bg">'.$national_quarterly_price.'</div>
                    <div class="item blue-light-bg">'.$national_monthly_price.'</div>
                    <div class="item blue-light-bg">'.$subscriber_count['national']['monthly'].'</div>
                    <div class="item blue-light-bg">'.$subscriber_count['national']['quarterly'].'</div>
                    <div class="item blue-light-bg">'.$subscriber_count['national']['onceoff'].'</div>

                    <div class="item sub-head-3">State</div>
                    <div class="item">'.$state_onceoff_price.'</div>
                    <div class="item">'.$state_quarterly_price.'</div>
                    <div class="item">'.$state_monthly_price.'</div>
                    <div class="item">'.$subscriber_count['state']['monthly'].'</div>
                    <div class="item">'.$subscriber_count['state']['quarterly'].'</div>
                    <div class="item">'.$subscriber_count['state']['onceoff'].'</div>

                    <div class="item sub-head-2">Postcode</div>
                    <div class="item blue-light-bg">'.$postcode_onceoff_price.'</div>
                    <div class="item blue-light-bg">'.$postcode_quarterly_price.'</div>
                    <div class="item blue-light-bg">'.$postcode_monthly_price.'</div>
                    <div class="item blue-light-bg">'.$subscriber_count['postcode']['monthly'].'</div>
                    <div class="item blue-light-bg">'.$subscriber_count['postcode']['quarterly'].'</div>
                    <div class="item blue-light-bg">'.$subscriber_count['postcode']['onceoff'].'</div>
                </a>
            ';
        }
    } else {
        $response = 'Error: Products could not be found, please try again.';
    }
} else {
    $response = $all_products['response'];
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
define('PAGE', 'Product List');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Product List');

//site author
define('AUTHOR', 'raremedia pty ltd');

//define the individual page styles (you can link or write inline) - delimiter: RETURN
define('STYLES', '
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/base.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/navigation.css" />
    <link rel="stylesheet" type="text/css" href="'.LOCAL.'web/css/product.page.css" />
');
//define the individual page javascript that runs at the start of the page - delimiter: RETURN
define('HEAD_JS', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: RETURN
define('FOOT_JS', '
    <script defer type="text/javascript" src="'.LOCAL.'web/script/jquery-3.2.1.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/foundation.min.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/what-input.js"></script>
    <script defer type="text/javascript" src="'.LOCAL.'web/script/product.page.js"></script>
');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(LOCAL.'secure/page/product.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
