<?php

class InputValidator implements IDatabaseAccess {

    private $db;
    private $regexes = array();
    private $validations;
    private $mandatories;
    private $sanitations;
    public $errorLogger;
    private $errorsCount = 0;

    public function __construct($validations = array(), $mandatories = array(), $sanitations = array("db")) {
        foreach ($validations as $field => $validation) {
            $this->setValidation($field, $validation);
        }
        $this->mandatories = $mandatories;
        $this->sanitations = $sanitations;

        $this->errorLogger = new ErrorLogger();

        $this->regexes["anything"] = new Validation("^[\d\D]{1,}\$", "");
        $this->regexes["hebrew"] = new Validation("^([א-ת]{2,}\s)*[א-ת]{2,}$", "יש להזין ערך בעברית (מותר רווח בין מילים)");
        $this->regexes["street"] = new Validation("^([א-ת]{2,}\s)*[א-ת]{2,} [1-9]([0-9]){0,2}$", "הערך אינו בתבנית של 'שם רחוב מספר בית'");
        $this->regexes["israeliID"] = new Validation("^[0-9]{9}$", "על תעודת הזהות להיות בת 9 ספרות");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function setValidation($field, $validation) {
        $this->validations[$field] = $validation;
    }

    public function addError($field, $msg) {
        $this->errorLogger->addError($field, $msg);
        $this->errorsCount++;
    }

    public function validate(&$items) {
        foreach ($items as $type => $value) {
            $this->validateItem($value, $type);
            $items[$type] = $this->sanitizeItem($value, $type);
        }
        return $this->errorsCount == 0;
    }

    public function sanitizeItem($value, $type) {
        $initialValue = $value;
        foreach ($this->sanitations as $sanitation) {
            switch ($sanitation) {
                case "db":
                    $value = $this->db->escape($value);
                    break;
            }
        }
        if ($value != $initialValue) {
            $this->addError($type, "הערך שהוזן לא תקין");
        }
        return $value;
    }

    public function validateItem($value, $type) {
        if (empty($value)) {
            if (array_search($type, $mandatories)) {
                $this->addError($type, "יש להזין ערך בשדה");
                return false;
            } else {
                return true;
            }
        }
        if (isset($this->validations[$type]) && isset($this->validations[$type], $this->regexes)) {
            $validation = $this->validations[$type];
            if (!preg_match("/" . $this->regexes[$validation]->regex . "/", $value)) {
                $this->addError($type, $this->regexes[$validation]->errorMsg);
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

}

?>