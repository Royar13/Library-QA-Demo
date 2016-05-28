<?php

class Action implements IDatabaseAccess {

    protected $db;
    public $id;
    public $description;
    public $userId;

    public function __construct($db, $id, $userId, $description) {
        $this->db = $db;
        $this->id = $id;
        $this->userId = $userId;
        $this->description = $description;
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    protected function createByQuery($query) {
        $bind["userId"] = $this->userId;
        $bind["id"] = $this->id;
        $bind["description"] = $this->description;
        $this->db->preparedQuery($query, $bind);
    }

}
