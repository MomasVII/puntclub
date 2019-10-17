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
    function get_link_by_url($db_name, $url = null)
    {
        //connect to the db
        $db_filename = 'secure/mysqlite_db/' . $db_name . '.db';
        $db = new SQLite3($db_filename);
        if (!$db) {
            return ['label' => ''];
        }
        if (!$url) {
            $url = $this->get_current_uri();
        }
        //query the links
        $stmt = $db->prepare('SELECT * FROM `link` WHERE `enabled` = 1 AND url = :url  ORDER BY `order` LIMIT 1');
        $stmt->bindValue(':url', $url, SQLITE3_TEXT);

        //build the html
        $data = $stmt->execute()->fetchArray();
        if (!isset($data['label'])) {
            return ['label' => 'index'];
        }
        return $data;
    }

    function get_current_uri()
    {
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

    /**
     * Turns an array to html attributes
     * @param $options
     * @return string
     */
    function array_to_html_attributes($options)
    {
        array_walk(
            $options,
            function (&$value, $name) {//TODO: check this works in current PHP version
                $value = $name . '="' . $value . '"';
            }
        );
        return implode(' ', $options);
    }

    /**
     * recursive function to print an multilevel tree , taken from https://stackoverflow.com/questions/26003141/php-tree-ul-li-hierarchy-menu-from-array
     * @param $branch
     * @param $allData
     * @param string $attributes
     * @return string
     */
    function renderMenu($branch, $allData, $attributes = '')
    {
        $render = '<ul ' . $attributes . '>';
        foreach ($branch as $link) {
            //convert the options from a string like '{data-test:"something"}'  to 'data-test="something"' so it can be added to the html
            $options = json_decode($link['options'], true);
            //$options['class'] = '';
            $options_as_attributes = $this->array_to_html_attributes($options);

            //set the item-id class on the "li" tag
            $li_attributes_array = array('class' => 'item-' . $link['id']);
            //set the parent class on the "li" tag
            if (!empty($allData[$link['id']]['children'])) {
                $li_attributes_array['class'] .= ' parent';
            }
            //set the active class on the "li" tag
            if ($this->is_active($link['url']) || $allData[$link['id']]['active_by_child']) {
                $li_attributes_array['class'] .= ' ' . $link['active_state'];
            }
            //render the <li>
            $render .= '<li '. $this->array_to_html_attributes($li_attributes_array + $options) . ' ><a href="'.$link['url'].'" title="'.$link['title'].'"> ' . $link['text'] . '</a>';
            //if menu has children
            if (!empty($allData[$link['id']]['children'])) {
                //turn the child options from json to array
                $attributes_array = json_decode($link['child_options'], true);
                //turn array into html attributes
                $attributes = $this->array_to_html_attributes($attributes_array);
                //render nested children
                $render .= $this->renderMenu($allData[$link['id']]['children'], $allData, $attributes);
            }
            $render .= '</li>';
        }
        return $render . '</ul>';
    }

    /**
     * Create a menu from a DB
     * @param $db_name
     * @return string
     */
    public function make_from_db($db_name)
    {
        $html = '';
        //connect to the db
        $db_filename = 'secure/mysqlite_db/' . $db_name . '.db';
        $db = new SQLite3($db_filename);
        if (!$db) {
            return $html;
        }
        //query the links
        $link_result = $db->query('SELECT *, 0 active_by_child FROM `link` WHERE `enabled` = 1 ORDER BY `order` ');
        //build the html
        $data = [];
        while ($link = $link_result->fetchArray()) {
            $data[$link['id']] = $link;
        }
        $topLevel = [];
        //get the first level of menu items
        foreach ($data as $link) {
            if (!$link['parent']) {
                $topLevel [] = $link;
                continue;
            }
            //link is active, make the parents active
            if ($this->is_active($link['url'])) {
                //if link is deep down the tree
                if ($link['level'] > 1) {
                    $current_link = $link;
                    //loop all ancestors and make them active
                    for ($x = $link['level']; $x >= 1; $x--) {
                        //if has no parent, means its the root, ignore
                        if (!$current_link['parent']) {
                            continue;
                        }
                        $data[$current_link['parent']]['active_by_child'] = 1;
                        $current_link = $data[$current_link['parent']];
                    }
                }
            }
            $data[$link['parent']]['children'][] = $link;
        }
        $html = $this->renderMenu($topLevel, $data);

        return $html;

    }

    /**
     * Check if url is active
     * @param $url
     * @return bool
     */
    public function is_active($url)
    {

        $current_uri = $this->get_current_uri();

        //remove the active tag value if this link isn't supposed to be active
        if (strcmp($current_uri, $url) !== 0) {
            return false;
        }
        return true;
    }

}
