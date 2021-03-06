<?php

class ErrorLogger {

    private $errors = array();

    public function addError($field, $msg) {
        $this->errors["fields"][$field][] = $msg;
    }

    public function addGeneralError($msg) {
        $this->errors["general"][] = $msg;
    }

    public function getErrors() {
        $output["success"] = false;
        $output["errors"] = $this->errors;
        return $output;
    }

    public function isValid() {
        return count($this->errors) == 0;
    }

}
