<?php

class FormField implements IDbDependency {

    private $db;
    public $value;
    public $validations;
    public $sanitations;
    public $isRequired;
    public $errors = array();

    public function __construct($value, $isRequired = false, $validations = array(), $sanitations = array("db")) {
        $this->value = $value;
        $this->validations = $validations;
        $this->isRequired = $isRequired;
        $this->sanitations = $sanitations;
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function addError($msg) {
        $this->errors[] = $msg;
    }

    public function sanitize() {
        $initialValue = $this->value;
        foreach ($this->sanitations as $sanitation) {
            switch ($sanitation) {
                case "db":
                    $this->value = $this->db->escape($this->value);
            }
        }
        if ($initialValue != $this->value)
            $this->addError("הערך שהוזן לא תקין");
    }

    public function validate() {
        if ($this->isRequired) {
            if (empty($this)) {
                $field->addError("חסר ערך בשדה");
                return false;
            }
        }
        foreach ($this->validations as $validation) {
            if (!preg_match($validation->regex, $this->value)) {
                $this->addError($validation->errorMsg);
            }
        }
        return count($this->errors) == 0;
    }

}

?>