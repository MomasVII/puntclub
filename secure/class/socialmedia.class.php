<?php
///////////////////////////////////////////////////////////////////////////////////
// Social Media Class
// Purpose: Provide social media API absraction
// Framework: Core
// Author: Lucas Jordan
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
#   socialmedia class
    ##  Instagram feed function
    ##  Twitter feed function
    ##  Facebook feed function
--------------------------------------------------------------*/

class socialmedia {

    /*--------------------------------------------------------------
    #   Instagram feed function
    --------------------------------------------------------------*/
    public function get_instagram_feed($url) {

        //get json data from instagram with curl
        $result = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);
        return $result;

    }


    /*--------------------------------------------------------------
    #   Twitter feed function
    --------------------------------------------------------------*/
    public function get_twitter_feed( $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret, $tweetCount, $screen_name ) {

        $result = '';

        // we are going to use "user_timeline"
        $twitter_timeline = "user_timeline";

        // specify number of tweets to be shown and twitter username
        // for example, we want to show 20 of Taylor Swift's twitter posts
        $request = array(
            'count' => $tweetCount,
            'screen_name' => $screen_name
        );

        // put oauth values in one oauth array variable
        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );

        // combine request and oauth in one array
        $oauth = array_merge($oauth, $request);

        // make base string
        $baseURI="https://api.twitter.com/1.1/statuses/$twitter_timeline.json";
        $method="GET";
        $params=$oauth;

        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        $base_info = $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);

        // get oauth signature
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        // make request
        // make auth header
        $r = 'Authorization: OAuth ';

        $values = array();
        foreach($oauth as $key=>$value){
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        }
        $r .= implode(', ', $values);

        // get auth header
        $header = array($r, 'Expect:');

        // set cURL options
        $options = array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $baseURI."?". http_build_query($request),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        );

        // retrieve the twitter feed
        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        curl_close($feed);

        // decode json format tweets
        $result = json_decode($json, true);

        return $result;

    }


    /*--------------------------------------------------------------
    #   Facebook login function
    --------------------------------------------------------------*/
    public function facebook_login( $fb, $redirectURL, $fbPermissions ) {

        /* Documentation */
        /* https://github.com/facebook/php-graph-sdk */

        $result = '';

         /* Facebook login */
         $helper = $fb->getRedirectLoginHelper();

         $permissions = $fbPermissions;
         $loginUrl = $helper->getLoginUrl("http://local.scotch/facebook.html", $permissions);

         echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

         /* Get facebook access token */
         try {
           $accessToken = $helper->getAccessToken();
         } catch(Facebook\Exceptions\FacebookResponseException $e) {
           // When Graph returns an error
           echo 'Graph returned an error: ' . $e->getMessage();
           exit;
         } catch(Facebook\Exceptions\FacebookSDKException $e) {
           // When validation fails or other local issues
           echo 'Facebook SDK returned an error: ' . $e->getMessage();
           exit;
         }

         if (! isset($accessToken)) {
           if ($helper->getError()) {
             header('HTTP/1.0 401 Unauthorized');
             echo "Error: " . $helper->getError() . "\n";
             echo "Error Code: " . $helper->getErrorCode() . "\n";
             echo "Error Reason: " . $helper->getErrorReason() . "\n";
             echo "Error Description: " . $helper->getErrorDescription() . "\n";
           } else {
             header('HTTP/1.0 400 Bad Request');
             echo 'Bad request';
           }
           exit;
         }

         // Logged in
         //echo '<h3>Access Token</h3>';
         //var_dump($accessToken->getValue());

         // The OAuth 2.0 client handler helps us manage access tokens
         $oAuth2Client = $fb->getOAuth2Client();

         // Get the access token metadata from /debug_token
         $tokenMetadata = $oAuth2Client->debugToken($accessToken);
         //echo '<h3>Metadata</h3>';
         //var_dump($tokenMetadata);

         // Validation (these will throw FacebookSDKException's when they fail)
         //$tokenMetadata->validateAppId( $appId ); // Replace {app-id} with your app id
         // If you know the user ID this access token belongs to, you can validate it here
         //$tokenMetadata->validateUserId('123');
         //$tokenMetadata->validateExpiration();

         if (! $accessToken->isLongLived()) {
           // Exchanges a short-lived access token for a long-lived one
           try {
             $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
           } catch (Facebook\Exceptions\FacebookSDKException $e) {
             echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
             exit;
           }
         }

         $_SESSION['fb_access_token'] = (string) $accessToken;

        return (string) $accessToken;

    }


    /*--------------------------------------------------------------
    #   Facebook get user details function - Use this to test the facebook connectivity
    --------------------------------------------------------------*/
    public function facebook_getUser( $fb, $sessionAccessToken ) {

        $result = '';

         /* Facebook login */
         $helper = $fb->getRedirectLoginHelper();

         /* Get User Profile using Facebook Graph API */
         try {
           // Returns a `Facebook\FacebookResponse` object
           $result = $fb->get('/me', $sessionAccessToken);
         } catch(Facebook\Exceptions\FacebookResponseException $e) {
           echo 'Graph returned an error: ' . $e->getMessage();
           exit;
         } catch(Facebook\Exceptions\FacebookSDKException $e) {
           echo 'Facebook SDK returned an error: ' . $e->getMessage();
           exit;
         }

         //$user = $response->getGraphUser();
         //$userName = 'Name: ' . $user['name'];

         return $result;

    }


    /*--------------------------------------------------------------
    #   Facebook get page feed function
    --------------------------------------------------------------*/
    public function facebook_getFeed( $fb, $sessionAccessToken, $page_id, $limit ) {

        /* Set variables */
        $returnArray = array();

        /* Get basic page information */
        try {
        // Requires the "read_stream" permission
            $info = $fb->get('/'.$page_id.'?fields=id,name', $sessionAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $facebookInformation = $info->getGraphNode();

        /* Get page profile picture */
        try {
        // Requires the "read_stream" permission
            $requestPicture = $fb->get('/'.$page_id.'/picture?redirect=false', $sessionAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $facebookPicture = $requestPicture->getGraphUser();

        /* Get facebook feed */
        try {
        // Requires the "read_stream" permission
            //$feed = $fb->get('/'.$page_id.'/feed?date_format=U,limit='.$limit, $sessionAccessToken);
            $feed = $fb->get('/'.$page_id.'/feed?date_format=U,limit='.$limit, $sessionAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $facebookFeed = $feed->getGraphEdge();

        /* Get facebook feed actions */
        try {
        // Requires the "read_stream" permission
            $feedActions = $fb->get('/'.$page_id.'/feed?fields=actions,limit='.$limit, $sessionAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $facebookFeedActions = $feedActions->getGraphEdge();

        /* Get facebook feed attachments */
        try {
        // Requires the "read_stream" permission
            $attachments = $fb->get('/'.$page_id.'/feed?fields=attachments{description,media,type,url,subattachments},limit='.$limit, $sessionAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $feedAttachments = $attachments->getGraphEdge();

        /* Get facebook feed actions count aka number of likes etc */
        try {
        // Requires the "read_stream" permission
            $actionCount = $fb->get('/'.$page_id.'/feed?fields=comments.limit(1).summary(true),likes.limit(1).summary(true),shares,limit='.$limit, $sessionAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $feedActionCount = $actionCount->getGraphEdge();

        /* Build custom result object */
        $i = 0;
        foreach ( $facebookFeed as $facebookStatus ) {

            /* Determin if post is of type message or story */
            /* NOTE: Stories has attachments/ sub attachments and are usually like uploading new images or chaging a profile image etc. */
            $is_story = false;
            if ( isset( $facebookStatus['story'] ) ) {
                $is_story = true;
            }
            $returnArray[$i]['is-story'] = $is_story;

            $attachmentItem = $feedAttachments->asArray();
            $actionItem = $facebookFeedActions->asArray();
            $returnArray[$i]['has-subattachment'] = false;

            /* Determin if post has sub attachments or not */
            if ( isset( $attachmentItem[$i]["attachments"][0]['subattachments'] ) ){
                $returnArray[$i]['has-subattachment'] = true;
            }

            /* Attach page name */
            $returnArray[$i]['name'] = $facebookInformation['name'];

            /*  Attach profile picture */
            if( isset( $facebookPicture['url'] ) ) {
                $returnArray[$i]['picture'] = $facebookPicture['url'];
            }

            /* Attach formatted post date NOTE: time is returned in Unix/ epoch time*/
            $createdAtData = $facebookStatus->getField('created_time');
            $unixTime = explode(",", $facebookStatus['created_time'] );
            $unixTime = $unixTime[0];

            $dt = new DateTime( "@".$unixTime );
            $dt->setTimeZone(new DateTimeZone('Australia/Melbourne'));
            // return created at in the format of 16 Jun 2017 12:49 PM
            $returnArray[$i]['created_at'] = $dt->format('d M Y h:i A');

            /* Check whether post is a message or story */
            if ( isset( $facebookStatus['message'] ) ) {
                $returnArray[$i]['message'] = $facebookStatus["message"];
            }

            if ( isset( $facebookStatus['story'] ) ) {
                $returnArray[$i]['story'] = $facebookStatus["story"];
            }

            $returnArray[$i]['postID'] = $facebookStatus["id"];

            /* Attach attachments */
            //$returnArray[$i]['attachment-type'] = $attachmentItem[$i]["attachments"][0]["type"];
            //$returnArray[$i]['attachment-url'] = $attachmentItem[$i]["attachments"][0]["url"];

            if ( isset( $attachmentItem[$i]["attachments"][0]["description"] ) ) {
                $returnArray[$i]['attachment-desc'] = $attachmentItem[$i]["attachments"][0]["description"];
            }
            if ( isset( $attachmentItem[$i]["attachments"][0]['media']['image']['src'] ) ) {
                $returnArray[$i]['attachment-img'] = $attachmentItem[$i]["attachments"][0]['media']['image']['src'];
            }

            /* Attach sub attachments */
            if ( isset( $attachmentItem[$i]["attachments"][0]['subattachments'] ) ){
                $q = 0;
                foreach ( $attachmentItem[$i]["attachments"][0]['subattachments'] as $subattachment ) {
                    $returnArray[$i]['sub-attachment'][$q]['description'] = $subattachment["description"];
                    $returnArray[$i]['sub-attachment'][$q]['img'] = $subattachment["media"]["image"]["src"];
                    $q++;
                }
            }

            /* Add post actions link */
            $returnArray[$i]['action-link'] = '';
            if ( isset( $actionItem[$i]["actions"][0]['link'] ) ){
                $returnArray[$i]['action-link'] =  $actionItem[$i]["actions"][0]['link'];
            }

            $returnArray[$i]['actions_likes'] = 0;
            $returnArray[$i]['actions_comments'] = 0;
            $returnArray[$i]['actions_shares'] = 0;

            $i++;
        }

        /* Attach number of like comments and shares */
        $a = 0;
        foreach ( $feedActionCount as $actions) {

            if( $a < $limit ) {

                $likesData =  $actions->getField('likes')->getMetaData();
                $commentDate = $actions->getField('comments')->getMetaData();
                $returnArray[$a]['actions_likes'] = $likesData["summary"]["total_count"];
                $returnArray[$a]['actions_comments'] = $commentDate["summary"]["total_count"];
                if( isset( $actions['shares'] ) ){
                    $returnArray[$a]['actions_shares'] = $actions['shares']['count'];
                }
            }

            $a++;
        }


        return $returnArray;

    }
}
