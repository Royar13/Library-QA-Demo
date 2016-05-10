<?php

abstract class FormValidator implements IDbDependency {

    protected $db;
    protected $fields = array();
    protected $errors = array();
    protected $validations = array();

    public function __construct() {
        $this->validations["anything"] = new Validation("^[\d\D]{1,}\$", "");
        $this->validations["hebrew"] = new Validation("^([א-ת]{2,}\s)*[א-ת]{2,}$", "יש להזין ערך בעברית (מותר רווח בין מילים)");
        $this->validations["street"] = new Validation("^([א-ת]{2,}\s)*[א-ת]{2,} [1-9]([0-9]){0,2}$", "הערך אינו בתבנית של 'שם רחוב מספר בית'");
        $this->validations["israeliID"] = new Validation("^[0-9]{9}$", "על תעודת הזהות להיות בת 9 ספרות");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    protected function getInputData($fieldNames, $param) {
        foreach ($fieldNames as $name) {
            if (isset($param[$name])) {
                $this->fields[$name] = Factory::makeFormField($param[$name]);
            } else {
                $this->fields[$name] = Factory::makeFormField("");
            }
        }
    }

    protected function mapDbData($row) {
        foreach ($row as $key => $val) {
            $this->fields[$key] = Factory::makeFormField($val);
        }
    }

    public function addError($msg) {
        $this->errors[] = $msg;
    }

    public function getFields() {
        $output = array();
        foreach ($this->fields as $name => $field) {
            $output[$name] = $field->value;
        }
        return $output;
    }

    public function getErrors() {
        $output = array();
        $output["general"] = $this->errors;
        foreach ($this->fields as $name => $field) {
            $output["fields"][$name] = $field->errors;
        }
        return $output;
    }

    protected function validateSyntax() {
        $passed = true;
        foreach ($this->fields as $field) {
            $field->sanitize();
            if (!$field->validate())
                $passed = false;
        }
        return $passed;
    }

    protected function isValid() {
        $count = count($this->errors);
        foreach ($this->fields as $field) {
            $count+=count($field->errors);
        }
        return $count == 0;
    }

    abstract public function validate();
}

?>