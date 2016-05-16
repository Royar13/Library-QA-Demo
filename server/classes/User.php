<?php

class User implements IDatabaseAccess {

    private $db;
    public $id;
    public $username;
    public $password;
    public $name;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function authenticate() {
        return isset($_SESSION["uid"]);
    }

    public function fetchLoggedUser() {
        if ($this->authenticate()) {
            try {
                $query = "select * from users where id=:uid";
                $bind[":uid"] = $_SESSION["uid"];
                $result = $this->db->preparedQuery($query, $bind);
                $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $userData = $rows[0];
                    $this->id = $userData["id"];
                    $this->username = $userData["username"];
                    $this->name = $userData["name"];
                    return true;
                }
            } catch (Exception $ex) {
                
            }
        }
        return false;
    }

    public function login(ErrorLogger $errorLogger) {
        try {
            $query = "select * from users where username=:username and password=:password";
            $bind[":username"] = $this->username;
            $bind[":password"] = $this->password;
            $result = $this->db->preparedQuery($query, $bind);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $userData = $rows[0];
                $_SESSION["uid"] = $userData["id"];

                $this->id = $userData["id"];
                $this->name = $userData["name"];
                return true;
            } else {
                $errorLogger->addGeneralError("פרטי התחברות שגויים");
                return false;
            }
        } catch (Exception $ex) {
            $errorLogger->addGeneralError("שגיאת מסד נתונים");
            return false;
        }
    }

    public function disconnect() {
        session_unset();
        session_destroy();
    }

}
