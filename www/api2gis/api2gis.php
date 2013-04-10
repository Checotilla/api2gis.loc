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

    public function LoadRubricatorToDB($forced_update=true) {
        //$xml = str_get_html($this->LoadRubricatorList());
        $xml = new simple_html_dom();
        $xml->load($this->LoadRubricatorList());
        global $DB;

        $children = $xml->find('rubric');
        $childs = array();
        $i = 0;
        foreach ($children as $element) {
            // чтение структуры рубрик
            $child[$i]['id'] = $element->children(0)->plaintext; // id
            $child[$i]['name'] = $this->clearFromCData($element->children(1)->plaintext); // name
            $child[$i]['alias'] = $this->clearFromCData($element->children(2)->plaintext); // alias

            var_dump($child);
            echo "<hr/>";
            
            
            // запись в базу структуры рубрик
            $table_name = 'rubricator';
            $result = $DB->select('SELECT *  FROM ?# WHERE ?#=? AND ?#=?', $table_name, "url", $url);
            if (count($result) > 0) {
                $fname = $result[0]['answer'];
                $answer = $this->ReadFile($fname);
            } else { //making request adding new element
                $answer = file_get_contents($url);
                $time = time();
                $path = 'data/answers/' . $time . '.xml';
                $this->WriteFile($path, $answer);
                $id = $DB->query('INSERT INTO ?#(?#, ?#, ?#) VALUES(?, ?, ?)', $table_name, 'time', 'url', 'answer', $time, $url, $path);
            }
        }
    }
    
    private function clearFromCData($text)
    {
        //012345678    210
        //<![CDATA[TEXT]]>
        $len = strlen($text);
        $result = substr($text, 9, $len - 12);
        return $result;
    }

}

?>
