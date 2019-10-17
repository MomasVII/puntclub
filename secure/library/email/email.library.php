<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // Email Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: Email Sending
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class email{

        //send an email
        public function send_array($subject, $from, $html_content, $to, $cc = '', $bcc = '', $host = ''){
            global $validate;

            //make sure required data exists and is valid
            if(
                $validate->sanitise_handler($subject, 'string', false) &&
                $validate->sanitise_handler($from, 'string', false) &&
                $validate->sanitise_handler($html_content, 'string', false) &&
                $validate->sanitise_handler($to, 'string', false)
            ){

                //check for defined host, else set as HTTP_HOST
                if(empty($host)){
                    $host = $_SERVER['HTTP_HOST'];
                }

                //check for multiple to recipients
                if(is_array($to)){
                    $parsed_to = '';
                    foreach($to as $t){
                        if($validate->sanitise_handler($t, 'email', false)){
                            $parsed_to .= $t.', ';
                        }
                    }
                    $parsed_to = substr($parsed_to, 0, -2);
                }elseif($validate->sanitise_handler($to, 'email', false)){
                    $parsed_to = $to;
                }

                //check that parsed_to was populated
                if(!empty($parsed_to)){

                    //check for multiple cc recipients
                    if(is_array($cc)){
                        $parsed_cc = 'Cc: ';
                        foreach($cc as $c){
                            $parsed_cc .= $c.', ';
                        }
                        $parsed_cc = substr($parsed_cc, 0, -2);
                    }elseif(!empty($cc) && $validate->sanitise_handler($cc, 'email', true)){
                        $parsed_cc = 'Cc: '.$cc;
                    }else{
                        $parsed_cc = '';
                    }

                    //check for multiple bcc recipients
                    if(is_array($bcc)){
                        $parsed_bcc = 'Bcc: ';
                        foreach($bcc as $b){
                            $parsed_bcc .= $b.', ';
                        }
                        $parsed_bcc = substr($parsed_bcc, 0, -2);
                    }elseif(!empty($bcc) && $validate->sanitise_handler($bcc, 'email', true)){
                        $parsed_bcc = 'Bcc: '.$bcc;
                    }else{
                        $parsed_bcc = '';
                    }

                    //build send headers
                    $headers = array();
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=UTF-8';
                    $headers[] = 'From: '.$from.' <no-reply@'.$host.'>';
                    if(!empty($parsed_cc)){$headers[] = $parsed_cc;}
                    if(!empty($parsed_bcc)){$headers[] = $parsed_bcc;}
                    $headers[] = 'Reply-To: '.$from.' <no-reply@'.$host.'>';
                    $headers[] = 'Subject: '.$subject;
                    $headers[] = 'X-Mailer: PHP/'.phpversion();

                    //attempt to send
                    $send = mail($parsed_to, $subject, $html_content, implode("\r\n", $headers));

                    if($send){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }
