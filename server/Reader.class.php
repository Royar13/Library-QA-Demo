<?php

class Reader implements IDbDependency {

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

    public function create(ReaderValidator $validator) {
        if ($validator->validate()) {
            $db->insert("readers", $validator->fields);
            return true;
        } else {
            return false;
        }
    }

}

?>