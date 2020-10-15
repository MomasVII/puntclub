<?php
    ///////////////////////////////////////////////////////////////////////////////////
    // MySQLi Library
    // Build: Rare_PHP_Core_Framework
    // Purpose: Database Access and Interaction
    // Version 1.0.1
    // Author: Gordon MacK
    // Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)
    ///////////////////////////////////////////////////////////////////////////////////

    class paginate
    {

        //define vars
        private $_record_array = array(); //array of actual record data that is going to be displayed
        private $_record_array_length = 0;
        private $_total_pages = 0;
        private $_starting_point = 0;
        private $_current_page = 1; //container for current page increment
        private $_ipp = 30; //container for items to display per page (items per page = ipp)
        private $_show_first_and_last; //container for first and last navigation boolean


        //slice the record data array down to page display chunk
        private function filter_results()
        {

            //find the length of the record array
            $this->_record_array_length = count($this->_record_array);

            //calculate the number of _total_pages
            $this->_total_pages = ceil($this->_record_array_length / $this->_ipp);

            //if the current page is greater than the total page count, force back to last page
            if ($this->_current_page > $this->_total_pages) {
                header('Location: '.LOCAL.'?p='.$this->_total_pages);
                die();
            }

            //calculate the starting point
            $this->_starting_point = ceil(($this->_current_page - 1) * $this->_ipp);

            //return the portion of results
            $result = array_slice($this->_record_array, $this->_starting_point, $this->_ipp);

            $this->_record_array = $result;
        }


        //set array and params
        public function set($record_array, $current_page = 1, $ipp = 30)
        {

            //set the record array container
            $this->_record_array = $record_array;

            //set the current page container
            $this->_current_page = $current_page;

            //set the items per page container
            $this->_ipp = $ipp;

            $this->filter_results();
        }


        //set whether to show first and last pagination navigation buttons
        public function show_first_and_last($show_first_and_last = false)
        {

            //set the show first and last boolean container
            $this->_show_first_and_last = $show_first_and_last;
        }


        //build pagination navigation, params array is used to pass unrelated vars that need to be retained
        public function build_navigation($params = array())
        {
            $return = '';

            //concatenate the get variables to add to the page numbering string
            $query_url = '';
            if (!empty($params) === true) {
                unset($params['page']);
                $query_url = '&amp;'.http_build_query($params);
            }

            //if there is more than one page
            if ($this->_total_pages > 1) {

                //set the 'previous page' link into the array if we are not on the first page
                if ($this->_current_page != 1) {

                    //add the first button if first and last are turned on
                    if ($this->_show_first_and_last) {
                        $return .= '<li class="pagination-previous"><a href="'.LOCAL.'?p=1'.$query_url.'"></a></li>';
                    }

                    //add previous button
                    $return .= '<li><a href="'.LOCAL.'?p='.($this->_current_page - 1).$query_url.'">&#8249;</a></li>';
                }

                //set all the page numbers and links to the array
                for ($j = 1; $j < ($this->_total_pages + 1); $j++) {
                    //if we are on the same page as the current loop, change styles and link
                    if ($this->_current_page === $j) {
                        $return .= '<li class="current"><span>'.$j.'</span></li>';
                    } else {
                        $return .= '<li><a href="'.LOCAL.'?p='.$j.$query_url.'">'.$j.'</a></li>';
                    }
                }

                //set the 'next page' link into the array if we are not on the last page
                if ($this->_current_page < $this->_total_pages) {
                    //add next button
                    $return .= '<li><a href="'.LOCAL.'?p='.($this->_current_page + 1).$query_url.'">&#8250;</a></li>';

                    //add the last button if first and last are turned on
                    if ($this->_show_first_and_last) {
                        $return .= '<li class="pagination-next"><a href="'.LOCAL.'?p='.($this->_total_pages).$query_url.'"></a></li>';
                    }
                }

                return '<div id="pagination"><ul class="pagination" role="navigation" aria-label="Pagination">'.$return.'</ul></div>';
            }

            return false;
        }


        //return parameters for use elsewhere
        public function return_parameters()
        {
            $return = array();

            //the filtered original record content for print
            $return['content'] = $this->_record_array;

            //the length of the filtered record content
            $return['content_length'] = $this->_record_array_length;

            //the page we are currently on
            $return['current_page'] = $this->_current_page;

            //the total number of pages
            $return['total_pages'] = $this->_total_pages;

            //the count id (original array position) of the first row
            $return['first_row'] = $this->_starting_point;

            //the calculated count id of the last row
            $return['last_row'] = $this->_starting_point + $this->_ipp;

            return $return;
        }
    }
