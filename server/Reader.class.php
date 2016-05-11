<?php

class Reader implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $city;
    public $street;
    public $readerType;
    public $maxBooks;
    public $status;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function create(ErrorLogger $errorLogger, $userId) {
        $this->validateReaderType($errorLogger);
        $this->validateMaxBooks($errorLogger);
        $this->validateID($errorLogger);
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

    public function validateReaderType($errorLogger) {
        $value = $this->readerType;

        $result = $this->db->query("select id from reader_types where id='{$value}'");
        if (mysqli_num_rows($result) != 1) {
            $errorLogger->addError("readerType", "סוג קורא לא תקין");
        }
    }

    public function validateMaxBooks($errorLogger) {
        $value = $this->maxBooks;

        $result = $this->db->query("select maxBooks from allowed_books_num where maxBooks='{$value}'");
        if (mysqli_num_rows($result) != 1) {
            $errorLogger->addError("maxBooks", "מספר ספרים לא תקין");
        }
    }

    public function validateID($errorLogger) {
        $value = $this->id;

        $result = $this->db->query("select id from readers where id='{$value}'");
        if (mysqli_num_rows($result) > 0) {
            $errorLogger->addError("id", "ת.ז. כבר קיימת במערכת");
            return;
        }

        $counter = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            $digit = intval($value[$i]);
            $incNum = $digit * (($i % 2) + 1);
            $counter+=($incNum > 9) ? $incNum - 9 : $incNum;
        }
        if ($counter % 10 != 0) {
            $errorLogger->addError("id", "ת.ז. לא תקינה");
        }
    }

}

?>