<?php

class FormValidator {

    public $fields = array();
    public $errors = array();
    public static $validations = array();

    public function __construct() {
        self::$validations["anything"] = new Validation("^[\d\D]{1,}\$", "");
        self::$validations["date"] = new Validation("^[0-9]{4}[-/][0-9]{1,2}[-/][0-9]{1,2}\$", "השדה צריך להיות בפורמט תאריך");
    }

    public function reset() {
        $errors = array();
        foreach ($this->fields as $field) {
            $field->errors = array();
        }
    }

    public function addError($msg) {
        $this->errors[] = $msg;
    }

    public function outputErrors() {
        $output = array();
        $output["general"] = $this->errors;
        $output["fields"] = $this->fields;
        return $output;
    }

}

?>