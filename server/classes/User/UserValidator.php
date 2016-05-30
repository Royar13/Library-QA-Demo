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

    public function validateDelete($user, $loggedUserHierarchy) {
        if (!$this->validateIdExist($user->id))
            $this->addGeneralError("לא נמצא המשתמש");
        if (!$this->validateType($user->type, $loggedUserHierarchy))
            $this->addGeneralError("לא ניתן למחוק משתמש עם סוג משתמש בכיר ממך");
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

    public function validateType($type, $loggedUserHierarchy) {
        $query = "select hierarchy from user_types where id=:type";
        $bind[":type"] = $type;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) != 1)
            return false;
        $hierarchy = $rows[0]["hierarchy"];
        return ($loggedUserHierarchy <= $hierarchy);
    }

}
