<?php

class Param {

    private $param = array();

    public function __construct() {
        $arr = json_decode(file_get_contents("php://input"), true);
        if ($arr != null)
            $this->param = $arr;
    }

    public function get($key) {
        if (isset($this->param[$key])) {
            return $this->param[$key];
        } else {
            return "";
        }
    }

    public function &getArray() {
        return $this->param;
    }

    public function exist($key) {
        return isset($this->param[$key]);
    }

}
