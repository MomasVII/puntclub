<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAHQIruxo84BACRboVZAQS6ajFHPpl2SqOVDzy2rrfIIaLHZCJtwlL9fLZAAFhbR2CEFiZC3HhUf1Y6AOfO0GtNWYvFRxosrxwT1bqnmeJD4ThFHZCK0ZCoK8PpZBawrZAMOFWWzwyVNUmEBo4pVRAX34JXmNvYGepjqsnVBK0HLWAZDZD';

    /*initialize curl*/
    $ch = curl_init($url);
    /*prepare response*/
    $jsonData = '{
    "recipient":{
        "id":"' . $sender . '"
        },
        "message":{
            "text":"Your sender id is ' . $sender . '"
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
