<?php

class UserValidator extends InputValidator implements IDatabaseAccess {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->setValidation("username", "username");
        $this->setValidation("name", "hebrew");
        $this->setValidation("password", "password");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function validateUpdate($user) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateIdExist($user->id))
            $this->addGeneralError("לא נמצא המשתמש");

        return $this->isValid();
    }

    public function validateUsernameFree($username) {
        $query = "select id from users where username=:username";
        $bind[":username"] = $username;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) == 0);
    }

    public function validateIdExist($id) {
        $query = "select id from users where id=:id";
        $bind[":id"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) == 1);
    }

    public function validatePasswordRepeat($password, $passwordRepeat) {
        return ($password == $passwordRepeat);
    }

    public function validateCurrentPassword($id, $password) {
        $query = "select id from users where id=:id AND password=:password";
        $bind[":id"] = $id;
        $bind[":password"] = $password;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) == 1);
    }

}
