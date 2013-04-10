<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of api2gis
 *
 * @author VKosarev
 */
class Api2gis {

    public $key;
    public $request;

    public function __construct($key = 'ruhlhy8961') {
        $this->key = $key;
        $this->request = new Request();
    }

    private function startRequest($url, $output = "xml") {
        if (strpos($url, "?") !== false)
            $url = $url . "&version=1.3&key=" . $this->key . "&output=" . $output;
        else
            $url = $url . "?version=1.3&key=" . $this->key . "&output=" . $output;
        $request = $this->request;
        return $request->Make($url);
    }

    public function LoadProjectsList() {
        $url = "http://catalog.api.2gis.ru/project/list";
        return $this->startRequest($url);
    }

    public function LoadRubricatorList() {
        $url = "http://catalog.api.2gis.ru/rubricator?where=Астрахань&show_children=1";
        return $this->startRequest($url);
    }

    public function LoadRubricatorList($rubric, $page=1) {
        $url = "http://catalog.api.2gis.ru/searchinrubric?where=Астрахань&page=".$page."&pagesize=50&sort=name&what=".$rubric;
        return $this->startRequest($url);
    }

    
    public function LoadRubricatorToDB($forced_update = false) {
        //$xml = str_get_html($this->LoadRubricatorList());
        $xml = new simple_html_dom();
        $xml->load($this->LoadRubricatorList());
        global $DB;

        $rubrics = $xml->find('rubric');
        $rubric = array();
        $i = 0;
        foreach ($rubrics as $xmlrubric) {
            // чтение головных рубрик
            $rubric['id'] = $xmlrubric->children(0)->plaintext; // id
            $rubric['name'] = $this->clearFromCData($xmlrubric->children(1)->plaintext); // name
            $rubric['alias'] = $this->clearFromCData($xmlrubric->children(2)->plaintext); // alias

            echo '<pre>';
            var_dump($rubric);
            echo "</pre><hr/>";

            // запись в базу головных рубрик

            $table_name = 'rubricator';
            $id = 0;
            $result = $DB->select('SELECT *  FROM ?# WHERE ?#=? AND ?#=?', $table_name, "2gis_id", $rubric['id'], "name", $rubric['name']);
            if (count($result) > 0) {
                $id = $result[0]['id'];
                if ($forced_update) {
                    $DB->query('UPDATE ?# SET ?#=?, ?#=?, ?#=? WHERE ?#=?', $table_name, "2gis_id", $rubric['id'], "name", $rubric['name'], "alias", $rubric['alias'], 'id', $id);
                }
            } else { //making request adding new element
                $id = $DB->query('INSERT INTO ?#(?#, ?#, ?#) VALUES(?, ?, ?)', $table_name, "2gis_id", "name", "alias", $rubric['id'], $rubric['name'], $rubric['alias']);
            }
            $parent_id = $id;
            // чтение и запись в базу дочерних рубрик
            $child = array();
            $children = $xmlrubric->find('child');
            foreach ($children as $xmlchild) {
                $child['id'] = $xmlchild->children(0)->plaintext; // id
                $child['name'] = $this->clearFromCData($xmlchild->children(1)->plaintext); // name
                $child['alias'] = $this->clearFromCData($xmlchild->children(2)->plaintext); // alias

                var_dump($child);
                echo "<hr/>";

                // запись в базу дочерних рубрик

                $table_name = 'rubric';
                $id = 0;
                //$result = $DB->select('SELECT *  FROM ?# WHERE ?#=? AND ?#=? ', $table_name, "name", $child['name'], "rubricator_id", $rubric['id']);
                $result = $DB->select('SELECT *  FROM ?# WHERE ?#=? ', $table_name, "name", $child['name']);
                if (count($result) > 0) {
                    $id = $result[0]['id'];
                    if ($forced_update) {
                        //$id = $DB->query('UPDATE ?# SET ?#=? AND ?#=? AND ?#=? WHERE ?#=?', $table_name, "2gis_id", $child['id'], "name", $child['name'], "alias", $child['alias'], "rubricator_id", $parent_id, 'id', $id);
                        $id = $DB->query('UPDATE ?# SET ?#=?, ?#=?, ?#=? , ?#=? WHERE ?#=?', $table_name, "2gis_id", $child['id'], "name", $child['name'], "alias", $child['alias'], "rubricator_id", $parent_id, 'id', $id);
                    }
                } else { //making request adding new element
                    $id = $DB->query('INSERT INTO ?#(?#, ?#, ?#, ?#) VALUES(?, ?, ?, ?)', $table_name, "2gis_id", "name", "alias", "rubricator_id", $child['id'], $child['name'], $child['alias'], $rubric['id']);
                }
            }
        }
    }

    private function LoadFirmsToDB() {
        $xml = new simple_html_dom();
        $xml->load($this->LoadRubricatorList());
        global $DB;

        $rubrics = $xml->find('rubric');
        $rubric = array();
        $i = 0;
        foreach ($rubrics as $xmlrubric) {
            // чтение головных рубрик
            $rubric['id'] = $xmlrubric->children(0)->plaintext; // id
            $rubric['name'] = $this->clearFromCData($xmlrubric->children(1)->plaintext); // name
            $rubric['alias'] = $this->clearFromCData($xmlrubric->children(2)->plaintext); // alias

            echo '<pre>';
            var_dump($rubric);
            echo "</pre><hr/>";

            // запись в базу головных рубрик

            $table_name = 'rubricator';
            $id = 0;
            $result = $DB->select('SELECT *  FROM ?# WHERE ?#=? AND ?#=?', $table_name, "2gis_id", $rubric['id'], "name", $rubric['name']);
            if (count($result) > 0) {
                $id = $result[0]['id'];
                if ($forced_update) {
                    $DB->query('UPDATE ?# SET ?#=?, ?#=?, ?#=? WHERE ?#=?', $table_name, "2gis_id", $rubric['id'], "name", $rubric['name'], "alias", $rubric['alias'], 'id', $id);
                }
            } else { //making request adding new element
                $id = $DB->query('INSERT INTO ?#(?#, ?#, ?#) VALUES(?, ?, ?)', $table_name, "2gis_id", "name", "alias", $rubric['id'], $rubric['name'], $rubric['alias']);
            }
            $parent_id = $id;
            // чтение и запись в базу дочерних рубрик
            $child = array();
            $children = $xmlrubric->find('child');
            foreach ($children as $xmlchild) {
                $child['id'] = $xmlchild->children(0)->plaintext; // id
                $child['name'] = $this->clearFromCData($xmlchild->children(1)->plaintext); // name
                $child['alias'] = $this->clearFromCData($xmlchild->children(2)->plaintext); // alias

                var_dump($child);
                echo "<hr/>";

                // запись в базу дочерних рубрик

                $table_name = 'rubric';
                $id = 0;
                //$result = $DB->select('SELECT *  FROM ?# WHERE ?#=? AND ?#=? ', $table_name, "name", $child['name'], "rubricator_id", $rubric['id']);
                $result = $DB->select('SELECT *  FROM ?# WHERE ?#=? ', $table_name, "name", $child['name']);
                if (count($result) > 0) {
                    $id = $result[0]['id'];
                    if ($forced_update) {
                        //$id = $DB->query('UPDATE ?# SET ?#=? AND ?#=? AND ?#=? WHERE ?#=?', $table_name, "2gis_id", $child['id'], "name", $child['name'], "alias", $child['alias'], "rubricator_id", $parent_id, 'id', $id);
                        $id = $DB->query('UPDATE ?# SET ?#=?, ?#=?, ?#=? , ?#=? WHERE ?#=?', $table_name, "2gis_id", $child['id'], "name", $child['name'], "alias", $child['alias'], "rubricator_id", $parent_id, 'id', $id);
                    }
                } else { //making request adding new element
                    $id = $DB->query('INSERT INTO ?#(?#, ?#, ?#, ?#) VALUES(?, ?, ?, ?)', $table_name, "2gis_id", "name", "alias", "rubricator_id", $child['id'], $child['name'], $child['alias'], $rubric['id']);
                }
            }
        }
    }

    private function clearFromCData($text) {
        //012345678    210
        //<![CDATA[TEXT]]>
        $len = strlen($text);
        $result = substr($text, 9, $len - 12);
        return $result;
    }

}

?>
