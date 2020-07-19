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
$required_libraries = array('upload');

//name the site classes you need in scope
$required_classes = array();

//initialize the framework
require(ROOT . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

//set default logic response
$response = '';
if (!empty($_POST['action'])) {
    if ($_POST['action'] == 'new_club') {
        $date = new DateTime();

        $insert_data = array(
            'Name' => $_POST['name'],
            'WeekStart' => $_POST['week_start'],
            'AmountBet' => $_POST['amount_bet'],
            'ROIRequired' => $_POST['roi_required'],
            'Deposited' => $_POST['deposited'],
            'Date' => $date->format('Y-m-d H:i:s'),
            'Active' => 1
        );
        //print_r($insert_data);
        $insert_result = $mysqli_db->insert('clubs', $insert_data);
    }
}

$userID = 5;
$clubs = "";
//Get all clubs associated with user
$allclubs = $mysqli_db->query('select clubusers.*, users.Name , clubs.* from clubusers inner join users on clubusers.UserID = users.ID inner join clubs on clubusers.ClubID = clubs.ID where users.ID = '.$userID, 100);
foreach($allclubs as $ac){
    /*<div class="col-md-6">
        <div class="vertical_gradient">
            <div class="club_details">
                <h1><?=$myClubname?></h1>
                <p>Total Won: $<?=number_format((float)$totalWon, 2, '.', '')?></p>
                <p>Total Bet: $<?=number_format((float)$total, 2, '.', '')?></p>
                <?=$roi?>
                <!--p>Bank: $000.00</p-->
                <p>Bonus Bets: Won $<?=number_format((float)$totalWonBB, 2, '.', '')?> out of $<?=number_format((float)$totalBB, 2, '.', '')?></p>
                <p>Total: $<?=number_format((float)$totalWon, 2, '.', '')?></p>
                <p>Week Starts: <?=$weekStarts?></p>
            </div>
        </div>
    </div>*/
}

// INCLUDE DEFINITIONS /////////////////////////////////////////////////////////////////////////////////////////////

//head include
define('HEAD', ROOT . 'secure/include/head.include.php');

//foot include
define('FOOT', ROOT . 'secure/include/foot.include.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// HEAD DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the name of the individual page  - delimiter: N/A
define('PAGE', 'Home');

//define the individual page description - delimiter: N/A
define('DESCRIPTION', 'Sign Up to Punt.Club');

//define the individual page styles - delimiter: COMMA
define('STYLES', '
    '.ROOT. 'web/style/base.css,
    '.ROOT. 'web/style/header.css,
    '.ROOT. 'web/style/home.css,
    '.ROOT. 'web/style/signup.css,
    '.ROOT. 'web/style/footer.css,
    '.ROOT. 'web/style/bootstrap.min.css,
    '.ROOT. 'web/style/all.css,
    '.ROOT. 'web/style/typography.css,
    '.ROOT. 'web/style/gradientbutton.css
');

//define the individual page javascript that runs at the start of the page - delimiter: COMMA
define('HEAD_JS', '
    '.ROOT.'web/script/jquery-3.2.1.min.js
');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: COMMA
define('FOOT_JS', '
    '.ROOT.'web/script/index.page.js,
    '.ROOT.'web/script/bootstrap.min.js
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/newclub.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
