<?php

abstract class FormValidator implements IDbDependency {

    protected $fields = array();
    protected $errors = array();
    protected $validations = array();

    public function __construct() {
        $this->validations["anything"] = new Validation("^[\d\D]{1,}\$", "");
        $this->validations["date"] = new Validation("^[0-9]{4}[-/][0-9]{1,2}[-/][0-9]{1,2}\$", "השדה אינו בפורמט תאריך");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function issetInputData($fieldNames, $param) {
        foreach ($fieldNames as $name) {
            if (!isset($param[$name])) {
                return false;
            }
        }
        return true;
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

    abstract public function validate();
}

?>