<?php

abstract class InputValidator {

    private $regexes = array();
    private $validations;
    protected $mandatories;
    public $errorLogger;

    public function __construct($validations = array(), $mandatories = array()) {
        foreach ($validations as $field => $validation) {
            $this->setValidation($field, $validation);
        }
        $this->mandatories = $mandatories;

        $this->errorLogger = new ErrorLogger();

        $this->regexes["hebrew"] = new Validation("^([א-ת][']?[א-ת]?[.]?){2,}(\s([א-ת][']?[א-ת]?[.]?){2,})*$", "יש להזין ערך בעברית");
        $this->regexes["hebrewTitle"] = new Validation("^([א-ת][']?[א-ת]?[.]?([:]|[,])?){2,}(\s([א-ת][']?[א-ת]?[.]?([:]|[,])?){2,})*$", "יש להזין ערך בעברית");
        $this->regexes["street"] = new Validation("^([א-ת][']?[א-ת]?[.]?){2,}(\s([א-ת][']?[א-ת]?[.]?){2,})* [1-9]([0-9]){0,2}$", "הערך אינו בתבנית של 'שם רחוב מספר בית'");
        $this->regexes["israeliID"] = new Validation("^[0-9]{9}$", "על תעודת הזהות להיות בת 9 ספרות");
        $this->regexes["nonNegativeInt"] = new Validation("^(0|([1-9][0-9]{0,6}))$", "הערך צריך להיות מספר");
        $this->regexes["positiveInt"] = new Validation("^[1-9][0-9]{0,6}$", "הערך צריך להיות מספר");
    }

    public function setValidation($field, $validation) {
        $this->validations[$field] = $validation;
    }

    public function addError($field, $msg) {
        $this->errorLogger->addError($field, $msg);
    }

    public function addGeneralError($msg) {
        $this->errorLogger->addGeneralError($msg);
    }
    public function getErrors() {
        return $this->errorLogger->getErrors();
    }

    public function isValid() {
        return $this->errorLogger->isValid();
    }

    public function validateSyntax($obj) {
        foreach ($this->mandatories as $type) {
            if (!isset($obj->$type) || empty($obj->$type)) {
                $this->addError($type, "יש להזין ערך בשדה");
            }
        }
        foreach ($this->validations as $type=>$validation) {
            $this->validateItem($obj->$type, $type);
        }
        return $this->isValid();
    }

    public function validateItem($value, $type) {
        if (empty($value)) {
            //validate function gives error about mandatory fields without value
            return true;
        }
        if (isset($this->validations[$type]) && isset($this->regexes[$this->validations[$type]])) {
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
