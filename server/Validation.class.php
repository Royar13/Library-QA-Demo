<?php

class Validation {

    public $regex;
    public $errorMsg;

    public function __construct($regex, $errorMsg) {
        $this->regex = $regex;
        $this->errorMsg = $errorMsg;
    }
    public function validate($field) {
        
    }
}

?>
