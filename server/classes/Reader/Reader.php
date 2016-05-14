<?php

class Reader implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $city;
    public $street;
    public $readerType;
    public $maxBooks;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function create(ErrorLogger $errorLogger, $userId) {
        $this->validateReaderType($errorLogger);
        $this->validateMaxBooks($errorLogger);
        $this->validateID($errorLogger);
        if (empty($this->city) && !empty($this->street)) {
            $errorLogger->addError("city", "יש למלא גם את העיר");
        }
        if (!empty($this->city) && empty($this->street)) {
            $errorLogger->addError("street", "יש למלא גם את הרחוב");
        }
        if (!$errorLogger->isValid()) {
            return false;
        }

        $fields["id"] = $this->id;
        $fields["name"] = $this->name;
        $fields["city"] = $this->city;
        $fields["street"] = $this->street;
        $fields["readerType"] = $this->readerType;
        $fields["maxBooks"] = $this->maxBooks;
        try {
            $this->db->insert("readers", $fields);

            $fields = array();
            $fields["userId"] = $userId;
            $fields["readerId"] = $this->id;
            $fields["description"] = "המשתמש {user} יצר את הקורא {reader}";
            $this->db->insert("readers_actions", $fields);
            return true;
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים");
            return false;
        }
    }

    public function readAll() {
        return $this->db->query("SELECT readers.id, readers.name, readers.city, readers.street, reader_types.title as readerType, readers.maxBooks, readers.joinDate"
                        . " FROM readers"
                        . " JOIN reader_types"
                        . " ON readers.readerType=reader_types.id");
    }

    private function validateReaderType($errorLogger) {
        $query = "select id from reader_types where id=:readerType";
        $bind[":readerType"] = $this->readerType;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            $errorLogger->addError("readerType", "סוג קורא לא תקין");
        }
    }

    private function validateMaxBooks($errorLogger) {
        $query = "select maxBooks from allowed_books_num where maxBooks=:maxBooks";
        $bind[":maxBooks"] = $this->maxBooks;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1) {
            $errorLogger->addError("maxBooks", "מספר ספרים לא תקין");
        }
    }

    private function validateID($errorLogger) {
        $query = "select id from readers where id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $errorLogger->addError("id", "ת.ז. כבר קיימת במערכת");
            return;
        }

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

}
