<?php

class ReaderAction extends Action {

    public function create() {
        $query = "INSERT INTO readers_actions(userId, readerId, description) VALUES(:userId, :id, :description)";
        $this->createByQuery($query);
    }

    public function readAll() {
        $query = "select readers_actions.*, users.id, users.name as nameOfUser, users.username, readers.name as readerName"
                . " from readers_actions"
                . " join users on readers_actions.userId=users.id"
                . " left join readers on readers_actions.readerId=readers.id"
                . " where readerId=:readerId";
        $bind[":readerId"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        return $result;
    }

    public function formatDescription($description, $username, $readerName) {
        $description = str_replace("{user}", "\"$username\"", $description);
        $description = str_replace("{reader}", "\"$readerName\"", $description);
        return $description;
    }

}
