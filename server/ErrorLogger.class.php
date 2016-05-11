<?php

class ErrorLogger {

    private $errors = array();

    public function addError($field, $msg) {
        $this->errors["fields"][$field][] = $msg;
    }

    public function addGeneralError($msg) {
        $this->errors["general"][] = $msg;
    }

    public function printJSON() {
        if (count($this->errors) == 0) {
            $output["success"] = true;
        } else {
            $output["success"] = false;
            $output["errors"] = $this->errors;
        }
        echo json_encode($output);
    }

    public function isValid() {
        return count($this->errors) == 0;
    }

}

?>