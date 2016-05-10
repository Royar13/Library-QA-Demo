<?php

class ReaderValidator extends FormValidator {

    public function __construct() {
        parent::__construct();
        $param = json_decode(file_get_contents("php://input"), true);
        $fieldNames = array("id", "name", "city", "street", "maxBooks", "readerType");
        $this->getInputData($fieldNames, $param);
        $this->fields["id"]->isRequired = true;
        $this->fields["name"]->isRequired = true;
        $this->fields["maxBooks"]->isRequired = true;
        $this->fields["readerType"]->isRequired = true;
        $this->fields["id"]->validations = array($this->validations["israeliID"]);
        $this->fields["city"]->validations = array($this->validations["hebrew"]);
        $this->fields["street"]->validations = array($this->validations["street"]);
    }

    public function validate() {
        $this->validateSyntax();
        try {
            $this->validateReaderType($this->fields["readerType"]->value);
            $this->validateMaxBooks($this->fields["maxBooks"]->value);
            $this->validateID($this->fields["id"]->value);
            return $this->isValid();
        } catch (Exception $ex) {
            $this->addError("שגיאת מסד נתונים");
            return false;
        }
    }

    public function validateReaderType($value) {
        $result = $this->db->query("select id from reader_types where id='{$value}'");
        if (mysqli_num_rows($result) != 1) {
            $this->fields["readerType"]->addError("סוג קורא לא תקין");
        }
    }

    public function validateMaxBooks($value) {
        $result = $this->db->query("select maxBooks from allowed_books_num where maxBooks='{$value}'");
        if (mysqli_num_rows($result) != 1) {
            $this->fields["maxBooks"]->addError("מספר ספרים לא תקין");
        }
    }

    public function validateID($value) {
        $counter = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            $digit = intval($value[$i]);
            echo $digit."-";
            $incNum = $digit * (($i % 2) + 1);
            $counter+=($incNum > 9) ? $incNum - 9 : $incNum;
        }
        if ($counter % 10 != 0) {
            $this->fields["id"]->addError("ת.ז. לא תקינה");
        }
    }

}

?>