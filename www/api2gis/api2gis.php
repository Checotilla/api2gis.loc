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

    public $key='ruhlhy8961'; 

    public function __construct($key) {
        $this->key = $key;
    }

    private function startRequest($url) {
        if (strpos($url, "?")!== false)
            $url = $url."&version=1.3&key=".$this->key;
        else
            $url = $url."?version=1.3&key=".$this->key;
        return file_get_contents($url);
    }
    
    public function LoadProjectsList() {
        $url="http://catalog.api.2gis.ru/project/list";
        return $this->startRequest($url);
    }
    

}

?>
