<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Build: rare_core
// Version 2.1.1
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SCOPE SETTINGS AND INSTANTIATION ////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '');

//configure local directory reference (usually blank)
define('LOCAL', '');

//name the framework libraries you need in scope (cross dependencies mean the order matters)
$required_libraries = array(

);

//name the site classes you need in scope
$required_classes = array(
    'auth'
);

//initialize the framework
require(ROOT . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

//set default logic response
$response = '';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', ROOT . 'secure/include/head.include.php');

//foot include
define('FOOT', ROOT . 'secure/include/foot.include.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Page Title - Limit to 60 characters or 9 words');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'An accurate, keyword-rich description about 150 characters');

//define the individual page styles - delimiter: COMMA
define('STYLES','
    '.ROOT. 'web/style/foundation.min.css,
    '.ROOT. 'web/style/typography.css,
    '.ROOT. 'web/style/base.css,
    '.ROOT. 'web/style/header.css,
    '.ROOT. 'web/style/footer.css,
    '.ROOT. 'web/style/navigation.css,
    '.ROOT. 'web/style/overview.css,

    '.ROOT.'web/style/contact.page.css,
');

//define the individual page javascript that runs at the start of the page - delimiter: COMMA
define('HEAD_JS', ''
    //.ROOT.'web/css/YOUR_HEADER_JS_HERE.js,
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: COMMA
define('FOOT_JS', '
    '.ROOT.'web/script/jquery-3.2.1.min.js,
    '.ROOT.'web/script/loadsh.js,
    '.ROOT.'web/script/foundation.min.js,
    '.ROOT.'web/script/tweenmax.min.js,
    '.ROOT.'web/script/resizehandler.js,
    '.ROOT.'web/script/navigation.js,
    '.ROOT.'web/script/gaTrack.js,
    '.ROOT.'web/script/svg.js,
    '.ROOT.'web/script/index.page.js,

    '.ROOT.'web/script/formhandler.js,
    '.ROOT.'web/script/init.js,
    '.ROOT.'web/script/overview.js
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/contact.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
