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

$response = '';

//get free tier products
$free_tier_print = '';
$free_products = $product->get_published_free_tier();

//if products were found start print
if ($free_products['boolean']) {
    $free_tier_print .= '
        <table>
        <tbody class="two-col">
    ';

    foreach ($free_products['content'] as $c) {
        $free_tier_print .= '
                <tr>
                    <td><a href="'.ROOT.'free_display.html?id='.$c['product_id'].'" target="_blank">'.$c['name'].'</a></td>
                    <td><input type="text" class="in-flow" value="'.PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/free_display.html?id='.$c['product_id'].'" /></td>
                </tr>
            ';
    }

    $free_tier_print .= '</tbody></table>';
} else {
    $free_tier_print = $free_products['response'];
}


//get paid tier products
$paid_tier_print = '';
$paid_products = $product->get_published();

//if products were found start print
if ($paid_products['boolean']) {
    $paid_tier_print .= '
        <table>
        <tbody class="two-col">
    ';

    foreach ($paid_products['content'] as $c) {
        $paid_tier_print .= '
            <tr>
                <td><a href="'.ROOT.'product_display.html?id='.$c['product_id'].'" target="_blank">'.$c['name'].'</a></td>
                <td><input type="text" class="in-flow" value="'.PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/product_display.html?id='.$c['product_id'].'" /></td>
            </tr>
        ';
    }

    $paid_tier_print .= '</tbody></table>';
} else {
    $paid_tier_print = $paid_products['response'];
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
define('PAGE', 'Product Links');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Product Links');

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
require(LOCAL . 'secure/page/product_link.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
