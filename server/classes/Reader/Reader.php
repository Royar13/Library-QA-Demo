<?php

class Reader implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $city;
    public $street;
    public $readerType;
    public $maxBooks;
    public $joinDate;
    public $payments;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function create(ReaderValidator $validator, $userId) {
        if (!$validator->validateCreate($this))
            return false;

        $fields = $this->toArray();
        try {
            $this->db->insert("readers", $fields);

            $action = new BookAction($this->db, $this->id, $userId, "המשתמש {user} יצר את הקורא {reader}");
            $action->create();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function toArray() {
        $fields["id"] = $this->id;
        $fields["name"] = $this->name;
        $fields["city"] = $this->city;
        $fields["street"] = $this->street;
        $fields["readerType"] = $this->readerType;
        $fields["maxBooks"] = $this->maxBooks;
        return $fields;
    }

    public function update(ReaderValidator $validator, $userId) {
        if (!$validator->validateUpdate($this))
            return false;

        try {
            $fields = $this->toArray();

            $condition["id"] = $this->id;
            $this->db->update("readers", $fields, $condition);

            $action = new BookAction($this->db, $this->id, $userId, "המשתמש {user} עדכן את הקורא {reader}");
            $action->create();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function delete(ReaderValidator $validator, $userId) {
        if (!$validator->validateDelete($this))
            return false;

        try {
            $condition["id"] = $this->id;
            $this->db->delete("readers", $condition);

            $action = new ReaderAction($this->db, null, $userId, "המשתמש {user} מחק את הקורא \"{$this->name}\"");
            $action->create();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function readOne() {
        $query = "select readers.*, reader_types.bookCost from readers join reader_types on readers.readerType=reader_types.id where readers.id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $reader = $rows[0];
            $this->name = $reader["name"];
            $this->city = $reader["city"];
            $this->street = $reader["street"];
            $this->readerType = $reader["readerType"];
            $this->maxBooks = $reader["maxBooks"];
            $this->joinDate = $reader["joinDate"];
            $joinDate = new DateTime($this->joinDate);
            $now = new DateTime();
            $months = $now->diff($joinDate)->format("%m");
            $this->payments = $reader["bookCost"] * $this->maxBooks * $months;
            return true;
        }
        return false;
    }

    public function readAll() {
        return $this->db->query("SELECT COUNT(borrowed_books.id) as borrowsNum, readers.id, readers.name, readers.city, readers.street, reader_types.title as readerType, readers.maxBooks, readers.joinDate"
                        . " FROM readers"
                        . " JOIN reader_types ON readers.readerType=reader_types.id"
                        . " LEFT JOIN borrowed_books ON readers.id=borrowed_books.readerId AND boolReturn=0"
                        . " GROUP BY readers.id");
    }

}
