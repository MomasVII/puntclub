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
    } else if ($_POST['action'] == 'delete') {
        $mysqli_db->where('ID', $_POST['bet_id']);
        $delete_result = $mysqli_db->delete('bets');
    } else if ($_POST['action'] == 'new_bet') {

        /*--Upload bet slip image--
        if(isset($_FILES) && !empty($_FILES)){
            //set the destination directory
            $upload->set_destination(LOCAL.'web/uploads');

            //start the upload
            $upload->file($_FILES['bet']);

            $upload->create_new_filename('abs');

            //set maximum file size in megabytes
            $upload->set_max_file_size(1);

            //set allowed mime types as array TODO:Turn back on and get working
            //$upload->set_allowed_mime_types(array('image/png', 'image/jpeg'));

            $result = $upload->upload(); //set true to retain original file name

            if($result['status']){
                $print = '<p>Validated upload succeeded.</p>';
                //print_r($result); //uncomment to see raw data output
            }else{
                $print = '<p>Validated upload failed.</p>';
            }
        }*/


        $date = new DateTime();

        if($_POST['description'] == "") {
            $desc = 'None';
        } else {
            $desc = $_POST['description'];
        }

        if($_POST['bonusbet']) {
            $bb = 'Yes';
        } else {
            $bb = 'No';
        }

        $insert_data = array(
            'User' => $_POST['user'],
            'Amount' => $_POST['amount'],
            'Odds' => $_POST['odds'],
            'Description' => $desc,
            'Result' => 'Pending',
            'Club' => 1,
            'BonusBet' => $bb,
            'Date' => $date->format('Y-m-d H:i:s')
        );
        //print_r($insert_data);
        $insert_result = $mysqli_db->insert('bets', $insert_data);


        //Send message to Facebook
        $users_to_message = $mysqli_db->query('select * from users', 100);
        foreach($users_to_message as $utm){

            $sender = $utm['ChatID']; //sender facebook id
            if($sender != "") {

                switch ($_POST['user']) {
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
                $total_winning = $_POST['amount']*$_POST['odds'];
                $message = $user_bet." just placed a bet of $".$_POST['amount']." at $".$_POST['odds'].". That's a potential return of $".$total_winning."! ".$_POST['description']; //text that user sent

                $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAHQIruxo84BACRboVZAQS6ajFHPpl2SqOVDzy2rrfIIaLHZCJtwlL9fLZAAFhbR2CEFiZC3HhUf1Y6AOfO0GtNWYvFRxosrxwT1bqnmeJD4ThFHZCK0ZCoK8PpZBawrZAMOFWWzwyVNUmEBo4pVRAX34JXmNvYGepjqsnVBK0HLWAZDZD';

                /*initialize curl*/
                $ch = curl_init($url);
                /*prepare response*/
                $jsonData = '{
                "recipient":{
                    "id":"' . $sender . '"
                    },
                    "message":{
                        "text":"' . $message . '"
                    }
                }';
                /* curl setting to send a json post data */
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                if (!empty($message)) {
                    $result = curl_exec($ch); // user will get the message
                }
            }
        }

        //if($insert_result) { echo 'good'; } else { echo 'bad'; }
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

        if($ub['BonusBet'] == "No") {
            if($ub['Result'] == "Win") {
                $ub_won += $ub['Amount']*$ub['Odds'];
                $form .= '<div class="win"></div>';
            } else if ($ub['Result'] == "Loss") {
                $form .= '<div class="loss"></div>';
            }
            $usr_total += $ub['Amount'];
        }

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




$bets = $mysqli_db->query('select * from bets INNER JOIN users ON bets.User = users.ID where Club = 1 order by Date DESC', 100);

$pending_bets = '';
$resulted_bets = '';
$total = 0;
$totalWon = 0;

foreach($bets as $bs){

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
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="delete"/>
                            <button type="submit">
                                <i class="fas fa-times undo_red"></i>
                            </button>
                        </form>
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
                    <h3>'.$bs['Name'].'</h3>
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

        if($bs['BonusBet'] == "No") {
            if($bs['Result'] != "Win") {
                $totalWon += $bs['Amount'];
            }
            $total += $bs['Amount'];
        }
        $resulted_bets .= '<div class="bet_slip_container">
            <div class="vertical_gradient">
                <div class="bet_slip '.$winloss.'">
                    <h3>'.$bs['Name'].'</h3>
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
    '.ROOT.'web/script/dataTables.responsive.min.js,
');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// PAGE TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////////////

//require the template and content
require(ROOT . 'web/page/index.page.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
