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
$required_libraries = array();

//name the site classes you need in scope
$required_classes = array(
);

//initialize the framework
require(ROOT . 'secure/config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// LOGIC ///////////////////////////////////////////////////////////////////////////////////////////////////////////

//set default logic response
$response = '';



$users = $mysqli_db->query('select * from users', 100);

foreach($users as $usr){

    $query = 'select * from bets where User = '.$usr['ID'];
    $user_bets = $mysqli_db->query($query, 100);
    foreach($user_bets as $ub){
        $ub_won
        $ub_lost
    }


    $table = '<div class="leaderboard_content">
        <h4>'.$usr.'</h4>
        <h4>34%</h4>
        <h4>$123</h4>
        <h4>$34</h4>
        <div class="form">
            <div class="loss"></div>
            <div class="win"></div>
        </div>
    </div>'

}




$bets = $mysqli_db->query('select * from bets order by Date DESC', 100);

$pending_bets = '';
$resulted_bets = '';

foreach($bets as $bs){


    switch ($bs['User']) {
        case '1':
            $user_bet = 'Simon Jackson';
            break;
        case '2':
            $user_bet = 'Thomas Bye';
            break;
        case '3':
            $user_bet = 'Lachlan Pound';
            break;
        case '4':
            $user_bet = 'Alistair Holiday';
            break;
        case '5':
            $user_bet = 'Angus Hillman';
            break;
        case '6':
            $user_bet = 'Calvin Bransdon';
            break;
        case '7':
            $user_bet = 'Joel Leegood';
            break;
        case '8':
            $user_bet = 'Tom Dann';
            break;
    }

    if($bs['Description'] == "") { $desc = "None"; }
    else { $desc = $bs['Description']; }

    if($bs['Result'] == "Win") { $winloss = "winner"; }
    else if($bs['Result'] == "Loss") { $winloss = "loser"; }

    if($bs['Result'] == "Win") {
        $profit =  '<div class="winner_detail">
                        <h3>+$'.number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '').'</h3>
                    </div>';
    } else if($bs['Result'] == "Loss") {
        $profit =  '<div class="loser_detail">
                        <h3>-$'.number_format((float)$bs['Amount'], 2, '.', '').'</h3>
                    </div>';
    } else if($bs['Result'] == "Pending") {
        $profit =  '<div class="pending_detail">
                        <h3>+$'.number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '').'</h3>
                    </div>';
    }


    if($bs['Result'] == "Pending") {
        $pending_bets .= '<div class="bet_slip_container">
            <div class="vertical_gradient">
                <div class="bet_slip">
                    <h3>'.$user_bet.'</h3>
                    <hr />
                    <h5>Description:</h5>
                    <p>'.$desc.'</p>
                    <hr />
                    <div class="bet_details">
                        <div>
                            <h5>Odds:</h5>
                            <p>$'.number_format((float)$bs['Odds'], 2, '.', '').'</p>
                        </div>
                        <div>
                            <h5>Amount:</h5>
                            <p>$'.number_format((float)$bs['Amount'], 2, '.', '').'</p>
                        </div>
                    </div>
                    <hr />
                    <div class="bet_details">
                        <div>
                            <h5>Date:</h5>
                            <p>'.date("Y-m-d", strtotime($bs['Date'])).'</p>
                        </div>
                    </div>
                </div>
                '.$profit.'
            </div>
        </div>';
    } else if($bs['Result'] != "Pending") {
        if($bs['Result'] != "Win") {
            $totalWon += $bs['Amount'];
        }
        $total += $bs['Amount'];
        $resulted_bets .= '<div class="bet_slip_container">
            <div class="vertical_gradient">
                <div class="bet_slip '.$winloss.'">
                    <h3>'.$user_bet.'</h3>
                    <hr />
                    <h5>Description:</h5>
                    <p>'.$desc.'</p>
                    <hr />
                    <div class="bet_details">
                        <div>
                            <h5>Odds:</h5>
                            <p>$'.number_format((float)$bs['Odds'], 2, '.', '').'</p>
                        </div>
                        <div>
                            <h5>Amount:</h5>
                            <p>$'.number_format((float)$bs['Amount'], 2, '.', '').'</p>
                        </div>
                    </div>
                    <hr />
                    <div class="bet_details">
                        <div>
                            <h5>Date:</h5>
                            <p>'.$bs['Date'].'</p>
                        </div>
                    </div>
                </div>
                '.$profit.'
            </div>
        </div>';
    }

}

if(($totalWon/$total)*100 > 100) {
    $roi = '<p class="green">ROI: <span><?=number_format((float)(($totalWon/$total)*100), 2, ".", "")?>%</span></p>';
} else if(($totalWon/$total)*100 < 100) {
    $roi = '<p class="red">ROI: <span>'.number_format((float)(($totalWon/$total)*100), 2, ".", "").'%</span></p>';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
define('DESCRIPTION', 'Welcome to Punt.Club');

//define the individual page styles - delimiter: COMMA
define('STYLES', '
    '.ROOT. 'web/style/base.css,
    '.ROOT. 'web/style/header.css,
    '.ROOT. 'web/style/home.css,
    '.ROOT. 'web/style/footer.css,
    '.ROOT. 'web/style/bootstrap.min.css,
    '.ROOT. 'web/style/all.css,
    '.ROOT. 'web/style/typography.css,
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
    '.ROOT.'web/script/index.page.js,
    '.ROOT.'web/script/bootstrap.min.js
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/index.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
