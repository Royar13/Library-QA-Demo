<?php

class User implements IDbDependency {

    private $db;
    private $id;
    public $username;
    private $password;
    public $name;
    public $formValidator;

    public function __construct() {
        $this->formValidator = new FormValidator();
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function authenticate() {
        return isset($_SESSION["uid"]);
    }

    public function login() {
        $this->formValidator->reset();
        try {
            $result = null;
            $param = json_decode(file_get_contents("php://input"), true);
            if ($this->authenticate()) {
                $result = $this->db->query("select * from users where id='{$_SESSION["uid"]}'");
            } else if (isset($param["username"]) && isset($param["password"])) {
                $data["username"] = $this->db->escape($param["username"]);
                $data["password"] = $this->db->escape($param["password"]);

                $result = $this->db->query("select * from users where username='{$data["username"]}' and password='{$data["password"]}'");
            } else
                return false;
            if ($result != null && mysqli_num_rows($result) == 1) {
                $userData = mysqli_fetch_assoc($result);
                $this->username = $userData["username"];
                $this->name = $userData["name"];
                $_SESSION["uid"] = $userData["id"];
                return true;
            }
            $this->formValidator->addError("פרטי התחברות שגויים");
            return false;
        } catch (Exception $e) {
            $this->formValidator->addError("התרחשה שגיאה במסד הנתונים");
            return false;
        }
    }

    public function disconnect() {
        session_unset();
        session_destroy();
    }

}

?>