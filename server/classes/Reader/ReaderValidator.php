<?php

class ReaderValidator extends InputValidator implements IDatabaseAccess {
    
    private $db;

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("id", "name", "maxBooks", "readerType");
        $this->setValidation("id", "israeliID");
        $this->setValidation("name", "hebrew");
        $this->setValidation("city", "hebrew");
        $this->setValidation("street", "street");
    }
    
    public function setDatabase($db) {
        $this->db = $db;
    }
    
    public function validate($obj) {
        if (!$this->validateSyntax($obj))
            return false;

        if (!$this->validateReaderType($obj->readerType))
            $this->addError("readerType", "סוג קורא לא תקין");

        if (!$this->validateMaxBooks($obj->maxBooks))
            $this->addError("maxBooks", "מספר ספרים לא תקין");

        if (!$this->validateCity($obj->city, $obj->street))
            $this->addError("city", "יש למלא גם את העיר");

        if (!$this->validateStreet($obj->city, $obj->street))
            $this->addError("street", "יש למלא גם את הרחוב");

        return $this->isValid();
    }

    public function validateCreate($obj) {
        if (!$this->validate($obj))
            return false;
        
        if (!$this->validateIDNew($obj->id))
            $this->addError("id", "ת.ז. כבר קיימת במערכת");
        
        return $this->isValid();
    }

    public function validateUpdate($obj) {
        if (!$this->validate($obj))
            return false;
        
        if (!$this->validateIDExist($obj->id))
            $this->addGeneralError("לא נמצא קורא עם תעודת הזהות");
        
        return $this->isValid();
    }

    public function validateCity($city, $street) {
        return !(empty($city) && !empty($street));
    }

    public function validateStreet($city, $street) {
        return !(!empty($city) && empty($street));
    }

    private function validateReaderType($readerType) {
        $query = "select id from reader_types where id=:readerType";
        $bind[":readerType"] = $readerType;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            return false;
        }
        return true;
    }

    private function validateMaxBooks($maxBooks) {
        $query = "select maxBooks from allowed_books_num where maxBooks=:maxBooks";
        $bind[":maxBooks"] = $maxBooks;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            return false;
        }
        return true;
    }

    private function validateIDNew($id) {
        $query = "select id from readers where id=:id";
        $bind[":id"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            return false;
        }
        return true;
        /* $counter = 0;
          for ($i = 0; $i < strlen($value); $i++) {
          $digit = intval($value[$i]);
          $incNum = $digit * (($i % 2) + 1);
          $counter+=($incNum > 9) ? $incNum - 9 : $incNum;
          }
          if ($counter % 10 != 0) {
          $errorLogger->addError("id", "ת.ז. לא תקינה");
          } */
    }

    public function validateIDExist($id) {
        $query = "select id from readers where id=:id";
        $bind[":id"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            return false;
        }
        return true;
    }

}
