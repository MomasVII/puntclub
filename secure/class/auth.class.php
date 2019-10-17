<?php
///////////////////////////////////////////////////////////////////////////////////
// Authentication Class
// Purpose: Provide various authentication methods
// Framework: Core
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class auth
{

    //generate CSRF token
    //PEN-TEST MOD: add CSRF token generator based on OWASP's example (https://www.owasp.org/index.php/PHP_CSRF_Guard)
    public function generate_csrf_token($form_name)
    {

        //create token using sha512
        $token = hash('sha512', mt_rand(0, mt_getrandmax()));

        //set token in session
        $_SESSION['token'][$form_name] = $token;

        return $token;
    }

    //validate CSRF token
    //PEN-TEST MOD: add CSRF token validator based on OWASP's example
    public function validate_csrf_token($form_name, $request_token)
    {

        //check that the token we are trying to validate against exists in the session
        if (isset($_SESSION['token'][$form_name]) && !empty($_SESSION['token'][$form_name])) {

            //save the session token then unset it
            $session_token = $_SESSION['token'][$form_name];
            unset($_SESSION['token'][$form_name]);

            //if the session token matches the request token; it's valid
            if ($session_token === $request_token) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
