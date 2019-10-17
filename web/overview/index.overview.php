<?php
//configure relative location to root directory eg: '../' or ''
define('ROOT', '../../');

//configure local directory reference (usually blank)
define('LOCAL', '');


$required_libraries = array(
    'validate',
);
/* @var $validate validate  */

//initialize the framework
require(ROOT.'secure/config.php');


// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

    //foot panel include
    //define('FOOTPANEL', ROOT.'secure/include/footpanel.include.php');

    //social include
    //define('SOCIAL', ROOT.'secure/include/social.include.php');


$fileList = scandir(ROOT.'web/overview');
//remove '.' and '..'
$key = array_search('.', $fileList);
unset($fileList[$key]);
$key = array_search('..', $fileList);
unset($fileList[$key]);

$fileName = $validate->sanitise_handler($_GET['name'], 'string', false);

$key = array_search($fileName.'.overview.php', $fileList);
if(!isset($fileList[$key])){
    return;
}
//echo json_encode($fileList);
require(LOCAL.$fileList[$key]);

?>
