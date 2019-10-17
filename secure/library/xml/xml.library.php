<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // XML Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: XML translation
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class xml{

        //callback function to sanitise data to make it safe for XML
        private function sanitise_xml(&$value){

            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }


        //return a response in an xml friendly format with an extra aggressive illegal character filter
        public function xml_response($type, $string, $root_node = '<root/>'){

            //make sure we're not missing any parameters
            if(! empty($type) && ! empty($string)){
                //recommended types: 'error', 'true', 'false'
                $xml = '
                <?xml version="1.0" encoding="utf-8" ?>
                <'.$root_node.'>
                    <type>'.$type.'</type>
                    <response>'.preg_replace('/[^A-Za-z0-9\-]/', '', $string).'</response>
                </'.$root_node.'>
            ';
                return $xml;
            }else{
                //error on failure makes it easier to track down a problem using error logs when the font end isn't HTML
                trigger_error('Could not complete XML response, parameters were missing.', E_USER_ERROR);
                return false;
            }
        }


        //looping function so that a multi-dimensional array can be converted properly.
        private function array_object_to_node($array, &$simple_xml){

            foreach($array as $key => $value){
                if(is_array($value)){
                    if(! is_numeric($key)){
                        $sub_node = $simple_xml->{'addChild'}($key);
                        $this->array_object_to_node($value, $sub_node);
                    }else{
                        $sub_node = $simple_xml->{'addChild'}('item_'.$key);
                        $this->array_object_to_node($value, $sub_node);
                    }
                }else{
                    $simple_xml->{'addChild'}($key, $value);
                }
            }
        }


        //convert an array to XML, doesn't matter if it's multi-dimensional or if it has inconsistent keys.
        public function array_to_xml($array, $root_node = '<root/>'){

            //make sure the array isn't empty, otherwise error out
            if(! empty($array)){
                //if a custom root node name has been set make sure it is safe
                if($root_node != '<root/>'){
                    //make sure the parent node name is xml friendly
                    $root_node = htmlspecialchars($root_node, ENT_QUOTES, 'UTF-8');
                    //add node syntax to name
                    $root_node = '<'.$root_node.'/>';
                }

                //make the array safe for XML
                array_walk_recursive($array, array($this, 'sanitise_xml'));

                //instantiate SimpleXML
                $simple_xml = new SimpleXMLElement($root_node);

                //call a looping function so that a multidimensional array can be translated without losing shape
                call_user_func_array(array($this, 'array_object_to_node'), array($array, &$simple_xml));

                //get the xml output from SimpleXML, can be saved to a file later if needed.
                $return = $simple_xml->asXML();

                return $return;
            }else{
                trigger_error('Could not convert array to XML, array was empty.', E_USER_ERROR);
                return false;
            }
        }


        //convert xml to plain array, this method maintains attributes
        function xml_to_array($xml){

            //check that we have data
            if(! empty($xml) && is_string($xml)){

                //instantiate SimpleXML and load XML buffer
                $simple_xml = new SimpleXMLElement($xml);

                //use a dirty trick to convert XML into an array while retaining all (most) data
                $array = json_decode(json_encode((array)$simple_xml), 1);
                $array = array($simple_xml->getName() => $array);

                //find what the root node is called then remove it from the plain array
                $root_node_key = key($array);
                $return = $array[$root_node_key];

                return $return;

            }else{
                //no need for a serious error here, php can handle it because the transaction is probably inbound
                return false;
            }

        }
    }