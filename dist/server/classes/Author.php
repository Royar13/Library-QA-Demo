<?php

class Author implements IDatabaseAccess {

    private $db;

    public function setDatabase($db) {
        $this->db = $db;
    }
    
    public function readAll() {
        return $this->db->query("select * from authors");
    }

}
