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
                $result = $this->db->query("select * from users where id='{$_SESSION["uid"]}'");
                if (mysqli_num_rows($result) == 1) {
                    $userData = mysqli_fetch_assoc($result);
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
            $result = $this->db->query("select * from users where username='{$this->username}' and password='{$this->password}'");
            if (mysqli_num_rows($result) == 1) {
                $userData = mysqli_fetch_assoc($result);
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