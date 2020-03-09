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

        $uploaded = false;
        $imageURL = '';
        $imageName = '';
        /*--Upload bet slip image--*/
        if(isset($_FILES) && !empty($_FILES)){
            //set the destination directory
            $upload->set_destination(LOCAL.'web/uploads');

            //start the upload
            $upload->file($_FILES['file']);

            //set maximum file size in megabytes
            $upload->set_max_file_size(1);

            //set allowed mime types as array TODO:Turn back on and get working
            //$upload->set_allowed_mime_types(array('image/png', 'image/jpeg'));

            $result = $upload->upload($_FILES['file']['name']); //set true to retain original file name
            $imageName = $_FILES['file']['name'];
            $imageURL = 'http://puntclub.undivided.games/web/uploads/'.$_FILES['file']['name'];
            $uploaded = true;

            if($result['status']){
                $print = '<p>Validated upload succeeded.</p>';
                //print_r($result); //uncomment to see raw data output
            } else {
                $print = '<p>Validated upload failed.</p>';
            }
        }


        $date = new DateTime();

        if($_POST['description'] == "") {
            $desc = 'None';
        } else {
            $desc = $_POST['description'];
        }
        if(isset($_POST['bonusbet'])) {
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
            'Image' => $imageName,
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

                $url = 'https://graph.facebook.com/v6.0/me/messages?access_token=EAAHQIruxo84BAI06AoCoqqIWItLZASsL7oUZA2IYGRBnRG3r1jQk0kUgLxZAZAPgakMJ6pK94Xa6BKfiLj09NvzrnWLZCwqq8SWnRP3JmXq9qNsJRkBdIfuOBXChZBK4A5zaZCApawMMvziwZCqHP8iG1uY3uWfjAkghqPg0l1DibgZDZD';

                /* initialize curl */
                $ch = curl_init($url);
                /* prepare response */
                $jsonData = '{
                    "messaging_type": "RESPONSE",
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


                // Send Image////////////////////

                if($uploaded){
                    $jsonData = '{
                        "messaging_type": "RESPONSE",
                        "recipient":{
                            "id":"' . $sender . '"
                        },
                        "message":{
                            "attachment":{
                                "type":"image",
                                "payload":{
                                    "url": "'.$imageURL.'"
                                }
                            }
                        }
                    }';
                    /* curl setting to send a json post data */
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    if (!empty($imageURL)) {
                        $result = curl_exec($ch); // user will get the message
                    }
                }

            }
        }

    }

    header('Location: '.$_SERVER['REQUEST_URI']);
    die;

}


// Get Club Details ////////////////////////////////////////////////////////////

$myClub = $mysqli_db->query('select * from clubs where ID = 1', 100);
$myClubID = $myClub[0]['ID'];
$myClubname = $myClub[0]['Name'];
$weekStarts = $myClub[0]['WeekStart'];  //Day of the week each week starts on i.e. 'Monday'
$clubStarted = $myClub[0]['Date'];  //Date the club started
$todaysDay = date("l"); //Get the day i.e. Tuesday
if($todaysDay != $weekStarts) { //Either get last Monday or the start of the week is today
    $myClubStartDay = 'last '.strtolower($weekStarts);
} else {
    $myClubStartDay = date('Y-m-d H:i:s', strtotime($weekStarts));
}


// Get Up Next Betters /////////////////////////////////////////////////////////

//weekStart is the last full weeks start day
//weekEnd is the last full weeks end day
$weekStart = date('Y-m-d H:i:s', strtotime($myClubStartDay.' -7 days'));
$weekEnd = date('Y-m-d H:i:s', strtotime($myClubStartDay));

$betters_this_week = '';
$betters_next_week = '';

//First get and loop through all the betters
$nextWeek = $mysqli_db->query('select clubusers.*, users.Name from clubusers inner join users on clubusers.UserID = users.ID where ClubID = 1', 100);
foreach($nextWeek as $nw){

    //Next Week ---------------
    //Select all bets from a certain user where the date equals this weeks current betting
    $dateNextSql = 'select bets.*, users.Name from bets inner join users on bets.User = users.ID where User = '.$nw['UserID'].' and Date > "'.$weekEnd.'"';
    $dateNextQuery = $mysqli_db->raw_query($dateNextSql, 100);

    $nw_won = 0;         //Amount the user has won
    $nw_usr_total = 0;   //Total amount bet
    $nextWeekROI = 0;    //Amount won / Total Bet
    $leftToSpend = 10;   //Current amount bet this week so far

    if($dateNextQuery) {
        foreach($dateNextQuery as $dq2){
            if($dq2['BonusBet'] == "No") {
                if($dq2['Result'] == "Win") {
                    $nw_won += $dq2['Amount']*$dq2['Odds'];
                }
                $nw_usr_total += $dq2['Amount'];
                $leftToSpend -= $dq2['Amount'];
            }
        }
    } else { //Set $nextWeekROI to positive to show if they aren't betting this week they definetely will next week
        $nw_usr_total = 1;
        $nw_won = 2;
    }

    $nextWeekROI = ($nw_won/$nw_usr_total)*100;
    if($nextWeekROI >= 100) {
        $betters_next_week .= '<li>'.$nw['Name'].'</li>';
    }


    ///Last weeks ROI ----------------
    //Select all bets that equal the start to last week to the end of last week
    $dateSql = 'select bets.*, users.Name from bets inner join users on bets.User = users.ID where User = '.$nw['UserID'].' and Date > "'.$weekStart.'" and Date < "'.$weekEnd.'"';
    //select bets.*, users.Name from bets inner join users on bets.User = users.ID where User = 1 and Date > "2019-11-04 00:00:00" and Date < "2019-11-11 00:00:00"

    $dateQuery = $mysqli_db->raw_query($dateSql, 100);

    $lw_won = 0;         //Last weeks won total
    $lw_usr_total = 0;   //Last weeks betting total
    $weekROI = 0;        //Last weeks ROI

    if($dateQuery) {
        foreach($dateQuery as $dq){
            if($dq['BonusBet'] == "No") {
                if($dq['Result'] == "Win") {
                    $lw_won += $dq['Amount']*$dq['Odds'];
                }
                $lw_usr_total += $dq['Amount'];
            }
        }
    } else {
        $lw_usr_total = 1;
        $lw_won = 2;
    }

    $weekROI = ($lw_won/$lw_usr_total)*100;
    if($weekROI >= 100) {
        $betters_this_week .= '<li>'.$nw['Name'].' ($'.$leftToSpend.')</li>'; //
    }
}

// Build Datatable /////////////////////////////////////////////////////////////

$users = $mysqli_db->query('select * from clubusers where ClubID = 1', 100);
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



$winStreak = 0; //Track longest win streak
$winStreakBool = false;
$winStreakRecord = 0;
$winStreakText = "";

$lossStreak = 0; //Track longest loss streak
$lossStreakBool = false;
$lossStreakRecord = 0;
$lossStreakText = "";

foreach($users as $usr){

    $query = 'select bets.*, users.Name from bets inner join users on bets.User = users.ID where User = '.$usr['UserID'].' order by Date desc';
    $user_bets = $mysqli_db->query($query, 100);
    $form = '<div class="form">';

    //Get current betters real name
    $name = $user_bets[0]['Name'];

    $ub_won = 0;
    $usr_total = 0;

    $winStreak = 0; //Reset win streak for next user
    $lossStreak = 0; //Reset win streak for next user

    foreach($user_bets as $ub){

        if($ub['BonusBet'] == "No") {
            if($ub['Result'] == "Win") {
                $winStreakBool = true;
                $ub_won += $ub['Amount']*$ub['Odds'];
                $form .= '<div class="win"></div>';
            } else if ($ub['Result'] == "Loss") {
                $winStreakBool = false;
                $form .= '<div class="loss"></div>';
            }
            $usr_total += $ub['Amount'];
        } else if($ub['BonusBet'] == "Yes") {
            if($ub['Result'] == "Win") {
                $winStreakBool = true;
                $ub_won += ($ub['Amount']*$ub['Odds'])-$ub['Amount'];
                $form .= '<div class="win"></div>';
            } else if ($ub['Result'] == "Loss") {
                $winStreakBool = false;
                $form .= '<div class="loss"></div>';
            }
        }

        if($winStreakBool) {
            $lossStreak = 0;
            $winStreak++;
            if($winStreak > $winStreakRecord) {
                $winStreakText = "Longest win streak: ".$name." (".$winStreak." wins)";
                $winStreakRecord = $winStreak;
            } else if($winStreak == $winStreakRecord) {
                $winStreakText .= " and ".$name." (".$winStreak." wins)";
            }
        } else {
            $winStreak = 0;
            $lossStreak++;
            if($lossStreak > $lossStreakRecord) {
                $lossStreakText = "Longest loss streak: ".$name." (".$lossStreak." losses)";
                $lossStreakRecord = $lossStreak;
            } else if($lossStreak == $lossStreakRecord) {
                $lossStreakText .= " and ".$name." (".$lossStreak." losses)";
            }
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

$table .= '</tbody>
</table>';

/////////////////////////////////////////////////////////////////




$bets = $mysqli_db->query('select bets.*, users.Name from bets INNER JOIN users ON bets.User = users.ID where Club = 1 order by Date DESC', 10000);

$pending_bets = '';
$resulted_bets = '';
$total = 0;
$totalWon = 0;
$totalBB = 0;
$totalWonBB = 0;

//$clubStarted
//Get one week from start week

$thisClubWeek = date('Y/m/d', strtotime($weekEnd)); //Get next Monday as start date

//Get current week -7 days
$prevWeek = strtotime($thisClubWeek);
$prevClubWeek = date('Y/m/d', $prevWeek);

$currentWeek = 0;
$totalWeekBet = 0; //Keep track of the total amount bet for the week
$totalWeekWon = 0; //Keep track of total amount won for the week
$weeksROI = array();
$peoplesTotalBet = array();
$peoplesTotalWon = array();
$weekSummary = '';

//Awards
$highestOdds = 0;
$highestAmountWon = 0;
$lowestOddsLost = 100;
$highestOddsWon = 0;



/*$clubWeek = strtotime("+7 day", strtotime($clubStarted)); //Week 1 end date
$weekCounter = 1;
$tableX = "[".
$userWeekSummary = array();*/

foreach($bets as $bs){

    //If we are at the end of the week sum up data collected
    /*if(date('Y/m/d', strtotime($bs['Date'])) > $clubWeek) {
        foreach ($peoplesTotalBet as $key => $value) {
            $userWeekSummary[$key][$weekCounter] = number_format((float)(($peoplesTotalWon[$key]/$value)*100), 2, ".", "");
        }

        $clubWeek = date('Y/m/d', strtotime("+7 day", strtotime($clubWeek))); //Go to start of next week

        $tableX .= $weekCounter+", ";
        $weekCounter++;
    }*/








    if(date('Y/m/d', strtotime($bs['Date'])) < $prevClubWeek) { //If bet has fallen outside currently checked week

        $weekSummary .= "<h3 class='summary_header'>WEEK STARTING ".$prevClubWeek."</h3><hr />";
        $weekSummary .= "<p>ROI: ".number_format((float)(($totalWeekWon/$totalWeekBet)*100), 2, ".", "")."%</p>";
        foreach ($peoplesTotalBet as $key => $value) {
            $weekSummary .= "<div class='peoplesROI'>";
            $weekSummary .= "<p>".$key.": ".number_format((float)(($peoplesTotalWon[$key]/$value)*100), 2, ".", "")."%</p>";
            $weekSummary .= "<p>+$".number_format((float)$peoplesTotalWon[$key], 2, ".", "")."</p>";
            $weekSummary .= "</div>";
        }

        $weeksROI[$currentWeek] = number_format((float)(($totalWeekWon/$totalWeekBet)*100), 2, ".", "");
        $currentWeek++; //Increment current week
        $nextDate = strtotime("-7 day", strtotime($prevClubWeek));
        $prevClubWeek = date('Y/m/d', $nextDate); //Find next weeks start date

        $totalWeekBet = 0; //Reset values for new week
        $totalWeekWon = 0;
        $peoplesTotalBet = array();
        $peoplesTotalWon = array();

    }

    if(!isset($peoplesTotalBet[$bs['Name']])) {
        $peoplesTotalBet[$bs['Name']] = 0;
    }
    if(!isset($peoplesTotalWon[$bs['Name']])) {
        $peoplesTotalWon[$bs['Name']] = 0;
    }

    $totalWeekBet += $bs['Amount']; //Keep track of total amount bet for the current week
    $peoplesTotalBet[$bs['Name']] += $bs['Amount']; //Keep track of each persons total amount bet for the week

    if($bs['Description'] == "") { $desc = "None"; }
    else { $desc = $bs['Description']; }

    if($bs['Result'] == "Win") { $winloss = "winner"; }
    else if($bs['Result'] == "Loss") { $winloss = "loser"; }


    ///////Build thumbs up/down and revert forms
    if($bs['Result'] == "Win") {
        $totalWeekWon += $bs['Amount']*$bs['Odds'];
        $peoplesTotalWon[$bs['Name']] += $bs['Amount']*$bs['Odds'];
        if($bs['BonusBet'] == "Yes") {
            $totalWonCard = number_format((float)(($bs['Odds']*$bs['Amount'])-$bs['Amount']), 2, '.', '');
        } else {
            $totalWonCard = number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '');
        }
        $profit =  '<div class="winner_detail">
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="undo"/>
                            <button type="submit">
                                <i class="fas fa-undo undo_green"></i>
                            </button>
                        </form>
                        <h3>+$'.$totalWonCard.'</h3>
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="delete"/>
                            <button type="submit">
                                <i class="fas fa-times undo_green"></i>
                            </button>
                        </form>
                    </div>';
    } else if($bs['Result'] == "Loss") {
        if($bs['BonusBet'] == "Yes") {
            $totalLostCard = "Bonus Bet";
        } else {
            $totalLostCard = number_format((float)$bs['Amount'], 2, '.', '');
        }
        $profit =  '<div class="loser_detail">
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="undo"/>
                            <button type="submit">
                                <i class="fas fa-undo undo_red"></i>
                            </button>
                        </form>
                        <h3>-$'.$totalLostCard.'</h3>
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
                            <input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
                            <input type="hidden" name="action" value="delete"/>
                            <button type="submit">
                                <i class="fas fa-times undo_red"></i>
                            </button>
                        </form>
                    </div>';
    } else if($bs['Result'] == "Pending") {
        if($bs['BonusBet'] == "Yes") {
            $totalPendingWon = number_format((float)(($bs['Odds']*$bs['Amount'])-$bs['Amount']), 2, '.', '');
        } else {
            $totalPendingWon = number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '');
        }
        $profit =  '<div class="pending_detail">
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
            				<input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
            				<input type="hidden" name="action" value="thumb_up"/>
                            <button type="submit">
                                <i class="fas fa-thumbs-up thumbs_up"></i>
                            </button>
                        </form>
                        <h3>+$'.$totalPendingWon.'</h3>
                        <form accept-charset="UTF-8" name="thumbs_up_form" action="'.$shortcut->clean_uri($_SERVER['REQUEST_URI']).'" method="post">
            				<input type="hidden" name="bet_id" value="'.$bs['ID'].'"/>
            				<input type="hidden" name="action" value="thumb_down"/>
                            <button type="submit">
                                <i class="fas fa-thumbs-down thumbs_down"></i>
                            </button>
                        </form>
                    </div>';
    }
    ////End Building forms

    $imageCode = '';
    if($bs['Image'] != "") {
        $imageCode = '<i class="fas fa-camera myImg" data-src="'.ROOT.'/web/uploads/'.$bs['Image'].'"></i>';
    }

    //Build Pending bets cards
    if($bs['Result'] == "Pending") {
        $pending_bets .= '<div class="bet_slip_container">
            <div class="vertical_gradient">
                <div class="bet_slip">
                    <div class="bet_header">
                        <h3>'.$bs['Name'].'</h3>
                        '.$imageCode.'
                    </div>
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

        //Calculate teams total won and total bonus bets won
        if($bs['BonusBet'] == "No") {
            if($bs['Result'] == "Win") {
                $totalWon += $bs['Amount']*$bs['Odds'];
            }
            $total += $bs['Amount'];
        } else if($bs['BonusBet'] == "Yes") {
            if($bs['Result'] == "Win") {
                $totalWonBB += ($bs['Amount']*$bs['Odds'])-$bs['Amount'];
            }
            $totalBB += $bs['Amount'];
        }
        $resulted_bets .= '<div class="bet_slip_container">
            <div class="vertical_gradient">
                <div class="bet_slip '.$winloss.'">
                    <div class="bet_header">
                        <h3>'.$bs['Name'].'</h3>
                        '.$imageCode.'
                    </div>
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


        if($bs['Result'] == "Loss") {
            //Track lowest odds lost
            if($bs['Odds'] < $lowestOddsLost) {
                $lowestOddsLost = $bs['Odds'];
                $lowestOddsLostText = "Lowest Odds Lost: ".$bs['Name']." (Bet $".$bs['Amount']." at $".$bs['Odds'].")";
            }
        } else if($bs['Result'] == "Win") {
            //Track highest odds won
            if($bs['Odds'] > $highestOddsWon) {
                $highestOddsWon = $bs['Odds'];
                $highestOddsWonText = "Highest Odds Won: ".$bs['Name']." (Bet $".$bs['Amount']." at $".$bs['Odds'].")";
            }

            if(($bs['Odds']*$bs['Amount']) > $highestAmountWon) {
                $highestAmountWon = $bs['Odds']*$bs['Amount'];
                $highestAmountWonText = "Highest Amount Won: ".$bs['Name']." (Bet $".$bs['Amount']." at $".$bs['Odds']." to win ".number_format((float)($bs['Odds']*$bs['Amount']), 2, '.', '').")";
            }
        }

    }

    //Track highest odds gambled
    if($bs['Odds'] > $highestOdds) {
        $highestOdds = $bs['Odds'];
        $highestOddsText = "Highest Odds Bet: ".$bs['Name']." ($".$bs['Odds'].", ".$bs['Result'].")";
    }

}

/*$chartsJS = "<script type='text/javascript'>var ctx = document.getElementById('myChart').getContext('2d');var myChart = new Chart(ctx, { type: 'line', data: { labels: [".$tableX."], datasets: [";
foreach ($userWeekSummary as $key => $value) {
    $chartsJS .= "{ data: [";
    for($x = 1; $x < $weekCounter; $x++) {
        $chartsJS .= $userWeekSummary[$key][$x].",";
    }
    $chartsJS .= "0], label: '".$userWeekSummary[$key]."', borderColor: '#3e95cd', fill: false }, ";

    //$userWeekSummary[$key][$weekCounter] = number_format((float)(($peoplesTotalWon[$key]/$value)*100), 2, ".", "");
}
$chartsJS = "]}, options: { title: { display: true, text: 'ROI' }}});</script>";

echo $chartsJS;*/

if(($totalWon/$total)*100 > 100) {
    $roi = '<p class="green">ROI: <span>'.number_format((float)(($totalWon/$total)*100), 2, ".", "").'%</span></p>';
} else if(($totalWon/$total)*100 < 100) {
    $roi = '<p class="red">ROI: <span>'.number_format((float)(($totalWon/$total)*100), 2, ".", "").'%</span></p>';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*$betsChart = $mysqli_db->query('select bets.*, users.Name from bets INNER JOIN users ON bets.User = users.ID where Club = 1 order by Date ASC', 100);

$arr = array(0 => array(id=>5,name=>"cat 1"),
             1 => array(id=>2,name=>"cat 2"),
             2 => array(id=>6,name=>"cat 1")
);

$money = 1;
foreach($betsChart as $bc){

    $chartData .= '["'.$money.'", ';

    $amount = 0;


    if($arrAmount[$bc['User']] == null) {
        $arrAmount[$bc['User']] = array();
    }

    $arrAmount[$bc['User']] += $bc['Amount'];
    if($arrAmount[$bc['User']] <= $money) {

        if($bc['Result'] != "Pending") {

            if($bc['Result'] == "Win") {
                $chartData .= ($bc['Amount']*$bc['Odds']);
            } else if($bc['Result'] == "Loss") {
                $chartData .= ($bc['Amount']);
            }


        }

    }

}

$chartData = '["Week", "Thomas", "Simon", "Tom", "Gus", "Lachy", "Ali", "Joel", "Cal"],';


                /*["", -5, 16.80, -10, -10, -5, 21.50, -5, -2],
                ["", -10, 31.15, -10, -10, -5, 29.50, -10, 7.15],
                ["", -10, -3, -10, -10, -10, 29.50, -10, 4.80],
                ["", -10, -5, -10, -10, -10, 29.50, -10, 4.80]';



$money = 5;
while($money < 100) {

    $chartData .= '["'.$money.'", ';

    for($x = 0; $x < 8; $x++) {
        //if($arr[0] )
    }
    //["", -5, 16.80, -10, -10, -5, 21.50, -5, -2],

    $money += 5;
}

print_r($arr);*/

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
    https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js
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
