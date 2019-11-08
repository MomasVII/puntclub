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

        private  $_template = '
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    <title>{{SUBJECT}}</title>
                    <style type="text/css">
                        #outlook a {padding:0;}
                        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
                        .ExternalClass {width:100%;}
                        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
                        #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
                        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
                        a img {border:none;}
                        .image_fix {display:block;}
                        p {margin: 1em 0;}
                        h1, h2, h3, h4, h5, h6 {color: black !important;}
                        h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
                        h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {color: red !important;}
                        h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {color: purple !important;}
                        table td {border-collapse: collapse;}
                        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
                        a {color: orange;}
                        @media only screen and (max-device-width: 480px) {
                            a[href^="tel"], a[href^="sms"] {
                                        text-decoration: none;
                                        color: blue;
                                        pointer-events: none;
                                        cursor: default;
                                    }

                            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                                        text-decoration: default;
                                        color: orange !important;
                                        pointer-events: auto;
                                        cursor: default;
                                    }
                        }

                        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
                            a[href^="tel"], a[href^="sms"] {
                                        text-decoration: none;
                                        color: blue;
                                        pointer-events: none;
                                        cursor: default;
                                    }
                            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                                        text-decoration: default;
                                        color: orange !important;
                                        pointer-events: auto;
                                        cursor: default;
                                    }
                        }
                    </style>
                </head>
                <body>
                    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
                        <tr>
                            <td valign="top">

                                <h2>{{SUBJECT}}</h2>

                                {{CONTENT}}

                                <p>Automated message from {{FROM}} ({{HOST}}). Please do not reply.</p>

                            </td>
                        </tr>
                    </table>
                </body>
            </html>
        ';

        //send an email
        public function send_array($subject, $from, $data_array, $to, $cc = '', $bcc = '', $host = ''){
            global $validate;

            //make sure required data exists and is valid
            if($validate->sanitise_handler($subject, 'string', false) && $validate->sanitise_handler($from, 'string', false) && !empty($data_array) && is_array($data_array) &&  !empty($to)){

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

                    //build content chunk to insert into html template
                    $content = '<table cellpadding="0" cellspacing="5" border="0">';
                    foreach($data_array as $k => $v){
                        $content .= '
                            <tr>
                                <td>'.$k.': '.$v.'</td>
                            </tr>
                        ';
                    }
                    $content .= '</table>';

                    //get html template and replace placeholders with data and content_chunk
                    $html = $this->_template;
                    $html = str_replace('{{SUBJECT}}', $subject, $html);
                    $html = str_replace('{{CONTENT}}', $content, $html);
                    $html = str_replace('{{FROM}}', $from, $html);
                    $html = str_replace('{{HOST}}', $host, $html);

                    //attempt to send
                    $send = mail($parsed_to, $subject, $html, implode("\r\n", $headers));

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