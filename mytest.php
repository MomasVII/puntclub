<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//configure relative location to root directory eg: '../' or ''
define('ROOT', '');

//initialize the framework
require(ROOT . 'secure/config.php');

// WEBHOOK ///////////////////////////////

/* receive and send messages */
//$input = json_decode(file_get_contents('php://input'), true);

//$sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id
$message = "Name not found. Please head to our website and sign-up first.";

$sender = "2322385887890847"; //sender facebook id
if($sender != "") {

    $user_bet = 'Simon Jackson';

    $total_winning = 123;
    $message = $user_bet." just placed a bet of $123 at $123. That's a potential return of $123! Desc"; //text that user sent

    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAHQIruxo84BACRboVZAQS6ajFHPpl2SqOVDzy2rrfIIaLHZCJtwlL9fLZAAFhbR2CEFiZC3HhUf1Y6AOfO0GtNWYvFRxosrxwT1bqnmeJD4ThFHZCK0ZCoK8PpZBawrZAMOFWWzwyVNUmEBo4pVRAX34JXmNvYGepjqsnVBK0HLWAZDZD';

    /* initialize curl */
    $ch = curl_init($url);
    /* prepare response */
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


    // Send Image////////////////////

    /*if($uploaded){
        $jsonData = '{
        "recipient":{
            "id":"' . $sender . '"
            },
            "message":{
                "attachment":{
                    "type":"image",
                    "payload":{
                        "url": "'.$imageURL.'",
                        "is_reusable":true
                    }
                }
            }
        }';
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        if (!empty($imageURL)) {
            $result = curl_exec($ch); // user will get the message
        }
    }*/

}
