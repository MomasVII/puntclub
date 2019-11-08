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
    if ($_POST['action'] == 'thumb_up') {
        $update_data = array(
            'Result' => 'Win',
        );
        $mysqli_db->where('ID', $_POST['bet_id']);
        $update_result = $mysqli_db->update('bets', $update_data);
    } else if ($_POST['action'] == 'thumb_down') {
        $update_data = array(
            'Result' => 'Loss',
        );
        $mysqli_db->where('ID', $_POST['bet_id']);
        $update_result = $mysqli_db->update('bets', $update_data);
    } else if ($_POST['action'] == 'undo') {
        $update_data = array(
            'Result' => 'Pending',
        );
        $mysqli_db->where('ID', $_POST['bet_id']);
        $update_result = $mysqli_db->update('bets', $update_data);
    } else if ($_POST['action'] == 'new_bet') {

        /*--Upload bet slip image--*/
        /*$target_dir = "web/uploads/";
        $target_file = $target_dir . basename($_FILES["bet"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["bet"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }*/


        $date = new DateTime();

        if($_POST['description'] == "") {
            $desc = 'None';
        } else {
            $desc = $_POST['description'];
        }

        $insert_data = array(
            'User' => $_POST['user'],
            'Amount' => $_POST['amount'],
            'Odds' => $_POST['odds'],
            'Description' => $desc,
            'Result' => 'Pending',
            'Date' => $date->format('Y-m-d H:i:s')
        );
        $insert_result = $mysqli_db->insert('bets', $insert_data);
    }
}

/*$chartData = '["Week", "Thomas", "Simon", "Tom", "Gus", "Lachy", "Ali", "Joel", "Cal"],
                ["", -5, 16.80, -10, -10, -5, 21.50, -5, -2],
                ["", -10, 31.15, -10, -10, -5, 29.50, -10, 7.15],
                ["", -10, -3, -10, -10, -10, 29.50, -10, 4.80],
                ["", -10, -5, -10, -10, -10, 29.50, -10, 4.80]';*/

$users = $mysqli_db->query('select * from clubs where ClubID = 1', 100);

$table = '';
$name = '';
$table = '<table id="table_id" class="display">
    <thead>
        <tr>
            <th><h4>Name</h4></th>
            <th><h4>ROI</h4></th>
            <th><h4>Wagered</h4></th>
            <th><h4>Won</h4></th>
            <th><h4>Form</h4></th>
        </tr>
    </thead>
    <tbody>';


$graph_title = '["Week';
$graph_weeks = array();
$weeks_count = 0;

foreach($users as $usr){

    $graph_title .= '", "';

    $query = 'select * from bets where User = '.$usr['UserID'].' order by Date desc';
    $user_bets = $mysqli_db->query($query, 100);
    $form = '<div class="form">';

    //Get current betters real name
    $queryName = 'select * from users where ID = '.$usr['UserID'];
    $user_name = $mysqli_db->query($queryName, 10);
    foreach($user_name as $un){
        $name = $un['Name'];
        $graph_title .= $name;
    }

    $ub_won = 0;
    $usr_total = 0;

    foreach($user_bets as $ub){

        if($ub['Result'] == "Win") {
            $ub_won += $ub['Amount']*$ub['Odds'];
            $form .= '<div class="win"></div>';
        } else if ($ub['Result'] == "Loss") {
            $form .= '<div class="loss"></div>';
        }
        $usr_total += $ub['Amount'];

    }
    $form .= '</div>';


    $table .= '<tr>
            <td><h4>'.$name.'</h4></td>
            <td><h4>'.number_format((float)(($ub_won/$usr_total)*100), 2, ".", "").'%</h4></td>
            <td><h4>$'.number_format((float)$usr_total, 2, '.', '').'</h4></td>
            <td><h4>$'.number_format((float)$ub_won, 2, '.', '').'</h4></td>
            <td>'.$form.'</td>
        </tr>';
}
$graph_title .= '"],';

$table .= '</tbody>
</table>';

/*foreach($users as $usr){

    $query = 'select * from bets where User = '.$usr['UserID'].' order by Date';
    $user_bets = $mysqli_db->query($query, 100);
    $form = '<div class="form">';

    $ub_won = 0;
    $usr_total = 0;

    foreach($user_bets as $ub){

        if($ub['Result'] == "Win") {
            $ub_won += $ub['Amount']*$ub['Odds'];
            $form .= '<div class="win"></div>';
        } else if ($ub['Result'] == "Loss") {
            $form .= '<div class="loss"></div>';
        }
        $usr_total += $ub['Amount'];

    }
    $form .= '</div>';




    $queryName = 'select * from users where ID = '.$usr['UserID'];
    $user_name = $mysqli_db->query($queryName, 10);
    foreach($user_name as $un){
        $name = $un['Name'];
    }

    $table .= '<div class="leaderboard_content">
        <h4>'.$name.'</h4>
        <h4>'.number_format((float)(($ub_won/$usr_total)*100), 2, ".", "").'%</h4>
        <h4>$'.number_format((float)$usr_total, 2, '.', '').'</h4>
        <h4>$'.number_format((float)$ub_won, 2, '.', '').'</h4>
        '.$form.'
    </div>';

}*/




$bets = $mysqli_db->query('select * from bets where Club = 1 order by Date DESC', 100);

$pending_bets = '';
$resulted_bets = '';
$total = 0;
$totalWon = 0;

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
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="undo"/>
                            <button type="submit">
                                <i class="fas fa-undo undo_green"></i>
                            </button>
                        </form>
                        <h3>+$'.number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '').'</h3>
                        <div class="spacing"><i class="fas fa-undo undo"></i></div>
                    </div>';
    } else if($bs['Result'] == "Loss") {
        $profit =  '<div class="loser_detail">
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="undo"/>
                            <button type="submit">
                                <i class="fas fa-undo undo_red"></i>
                            </button>
                        </form>
                        <h3>-$'.number_format((float)$bs['Amount'], 2, '.', '').'</h3>
                        <div class="spacing"><i class="fas fa-undo undo"></i></div>
                    </div>';
    } else if($bs['Result'] == "Pending") {
        $profit =  '<div class="pending_detail">
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
            				<input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
            				<input type="hidden" name="action" value="thumb_up"/>
                            <button type="submit">
                                <i class="fas fa-thumbs-up thumbs_up"></i>
                            </button>
                        </form>
                        <h3>+$'.number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '').'</h3>
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
            				<input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
            				<input type="hidden" name="action" value="thumb_down"/>
                            <button type="submit">
                                <i class="fas fa-thumbs-down thumbs_down"></i>
                            </button>
                        </form>
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
    $roi = '<p class="green">ROI: <span>'.number_format((float)(($totalWon/$total)*100), 2, ".", "").'%</span></p>';
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
    '.ROOT. 'web/style/datatables.css,
    '.ROOT. 'web/style/responsive.dataTables.min.css,
    '.ROOT. 'web/style/gradientbutton.css
');

//define the individual page javascript that runs at the start of the page - delimiter: COMMA
define('HEAD_JS', '
    '.ROOT.'web/script/jquery-3.2.1.min.js,
');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// FOOT DEFINITIONS ////////////////////////////////////////////////////////////////////////////////////////////////

//define the individual page javascript that runs at the end of the page - delimiter: COMMA
define('FOOT_JS', '
    '.ROOT.'web/script/index.page.js,
    '.ROOT.'web/script/bootstrap.min.js,
    '.ROOT.'web/script/datatables.js,
    '.ROOT.'web/script/dataTables.responsive.min.js
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/index.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
