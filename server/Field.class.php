<?php

class Field {

    public $name;
    public $value;
    public $validations;
    public $isRequired;
    public $errors = array();

    public function __construct($name, $value, $validations, $isRequired) {
        $this->name = $name;
        $this->value = $value;
        $this->validations = $validations;
        $this->isRequired = $isRequired;
    }

    public function addError($msg) {
        $errors[] = $msg;
    }

}

?>