<?php

class User implements IDbDependency {

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

    public function login(LoginValidator $validator) {
        $userData = null;
        if ($this->authenticate()) {
            try {
                $result = $this->db->query("select * from users where id='{$_SESSION["uid"]}'");
                if (mysqli_num_rows($result) == 1) {
                    $userData = mysqli_fetch_assoc($result);
                }
            } catch (Exception $e) {
                
            }
        }
        if ($userData == null) {
            if ($validator->validate()) {
                $userData = $validator->getFields();
            }
        }
        if ($userData != null) {
            $_SESSION["uid"] = $userData["id"];
            $this->username = $userData["username"];
            $this->name = $userData["name"];
            return true;
        } else {
            return false;
        }
    }

    public function disconnect() {
        session_unset();
        session_destroy();
    }

}

?>