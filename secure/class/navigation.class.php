<?php
///////////////////////////////////////////////////////////////////////////////////
// Navigation Class
// Purpose: Various navigation methods
// Framework: Core
// Author: Gordon MacK
// Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
///////////////////////////////////////////////////////////////////////////////////

class navigation
{

    //get a clean current page name
    function get_current_uri(){

        global $shortcut;
        //get the current URI
        $current_uri = $shortcut->clean_uri($_SERVER['REQUEST_URI']);

        //if the URI is blank then we're on index.html
        if ($current_uri == '/' || $current_uri == '') {
            $current_uri = 'index.html';
        }

        //strip slashes out of the URI
        $current_uri = str_replace('/', '', $current_uri);
        return $current_uri;
    }

    public function is_active($page = ''){

        $return = '';

        if(empty($page)){
            return $return;
        }

        //get a clean url
        $current_uri = $this->get_current_uri();
        $current_uri = str_replace('.html', '', $current_uri);

        //remove the active tag value if this link isn't supposed to be active
        if ($page !== $current_uri) {
            return $return;
        }

        $return = 'active';
        return $return;
    }

}
