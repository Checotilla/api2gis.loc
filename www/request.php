<?php

class Request {
    /* MakeRequest  /*
      /*************************************** */

    public function Make($url) {
        global $DB;
        $table_name = 'requests';
        $result = $DB->select('SELECT *  FROM ?# WHERE ?#=?', $table_name, "url", $url);
        if (count($result) > 0) {
            $fname = $result[0]['answer'];
            $answer = $this->ReadFile($fname);
        } else { //making request adding new element
            $answer = file_get_contents($url);

            $xml = new simple_html_dom();
            $xml->load($this->LoadRubricatorList());
            $response = $xml->find('response_code');
            if ($response[0] != 200)
                return "Error from api2Gis: response ".$response[0];
        
            $time = time();
            $path = 'data/answers/' . $time . '.xml';
            $this->WriteFile($path, $answer);
            $id = $DB->query('INSERT INTO ?#(?#, ?#, ?#) VALUES(?, ?, ?)', $table_name, 'time', 'url', 'answer', $time, $url, $path);
        }
        return $answer;
    }

    /* ReadFile */
    /*     * ************************************* */

    private function ReadFile($path) {
        if (!is_file($path)) {
            error("File $path does not exist.");
            return false;
        }
        if (!is_readable($path)) {
            error("File $path does not readable.");
            return false;
        }
        return file_get_contents($path);
    }

    /* WriteFile */
    /*     * ************************************* */

    private function WriteFile($path, $text) {
        return file_put_contents($path, $text);
    }

}

?>
