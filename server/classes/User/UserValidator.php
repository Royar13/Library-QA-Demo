<?php

class UserValidator extends InputValidator implements IDatabaseAccess {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("username", "name", "type");
        $this->setValidation("username", "username");
        $this->setValidation("name", "hebrew");
        $this->setValidation("password", "password");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function validateCreate($user) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateUsernameFree($user->username))
            $this->addError("username", "שם המשתמש תפוס");

        return $this->isValid();
    }

    public function validateUpdate($user) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateIdExist($user->id))
            $this->addGeneralError("לא נמצא המשתמש");

        return $this->isValid();
    }

    public function validateUpdatePassword($user) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateIdExist($user->id))
            $this->addGeneralError("לא נמצא המשתמש");
        if (!$this->validatePasswordRepeat($user->password, $user->passwordRepeat))
            $this->addError("passwordRepeat", "אימות הסיסמא לא זהה לסיסמא");
        if (!$this->validateCurrentPassword($user->id, $user->currentPassword))
            $this->addError("currentPassword", "סיסמא שגויה");
        
        return $this->isValid();
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
