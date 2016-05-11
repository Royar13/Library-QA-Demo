<?php

class Book implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $location;
    public $author;
    public $publisher;
    public $releaseYear;
    public $copies;

    public function setDatabase($db) {
        $this->db = $db;
    }

    //$datetime = date('Y-m-d H:i:s') ;
}
?>

