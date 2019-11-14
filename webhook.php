<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '');

//initialize the framework
require(ROOT . 'secure/config.php');

// WEBHOOK ///////////////////////////////
/* validate verify token needed for setting up web hook */
if (isset($_GET['hub_verify_token'])) {
    if ($_GET['hub_verify_token'] === 'my_stupid_verify_token') {
        echo $_GET['hub_challenge'];
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
    }
}

/* receive and send messages */
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['entry'][0]['messaging'][0]['message'])) {

    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id
    $message = $input['entry'][0]['messaging'][0]['message']['text']; //text that user sent
    $response = "Name not found. Please head to our website and sign-up first.";


    if($message == "/help") {

        $response = "/captain: Print highest ROI.";

    } else if($message == "/captain") {

        //Get all users in club
        $bestROI = 0;
        $bestUser = '';

        $users = $mysqli_db->query('select * from clubs inner join users ON clubs.UserID = users.ID where ClubID = 1', 100);
        foreach($users as $usr){
            $query = 'select * from bets where User = '.$usr['UserID'].' order by Date desc';
            $user_bets = $mysqli_db->query($query, 100);

            $ub_won = 0;
            $usr_total = 0;

            foreach($user_bets as $ub){

                if($ub['BonusBet'] == "No") {
                    if($ub['Result'] == "Win") {
                        $ub_won += $ub['Amount']*$ub['Odds'];
                    }
                    $usr_total += $ub['Amount'];
                }

            }

            $thisROI = (float)(($ub_won/$usr_total)*100);
            if($thisROI > $bestROI) {
                $bestROI = $thisROI;
                $bestUser = $usr['Name'];
            }
        }

        $response = "Look at me...".$bestUser." is the captain now. ROI: ".$bestROI."%.";
    } else {

        $sql = 'select * from users where Name = "'.$message.'"';
        $users_check = $mysqli_db->query($sql);

        //Check if user matches user message
        foreach($users_check as $uc){
            if($uc['Name'] == $message) {
                //If user found add chat ID
                $update_data = array(
                    'ChatID' => $sender,
                );
                $mysqli_db->where('Name', $message);
                $update_result = $mysqli_db->update('users', $update_data);

                $response = "Thank you. You will now receive updates when bets are placed.";
            }
        }
    }

    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAHQIruxo84BACRboVZAQS6ajFHPpl2SqOVDzy2rrfIIaLHZCJtwlL9fLZAAFhbR2CEFiZC3HhUf1Y6AOfO0GtNWYvFRxosrxwT1bqnmeJD4ThFHZCK0ZCoK8PpZBawrZAMOFWWzwyVNUmEBo4pVRAX34JXmNvYGepjqsnVBK0HLWAZDZD';

    /*initialize curl*/
    $ch = curl_init($url);
    /*prepare response*/
    $jsonData = '{
    "recipient":{
        "id":"' . $sender . '"
        },
        "message":{
            "text":"' . $response . '"
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
