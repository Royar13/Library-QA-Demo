<?php

class User implements IDatabaseAccess {

    private $db;
    public $id;
    public $username;
    public $password;
    public $repeatPassword;
    public $currentPassword;
    public $name;
    public $type;
    public $typeTitle;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function authenticate() {
        return isset($_SESSION["uid"]);
    }

    public function fetchLoggedUser() {
        if ($this->authenticate()) {
            $query = "select users.*, user_types.title as typeTitle from users left join user_types on users.type=user_types.id where users.id=:uid";
            $bind[":uid"] = $_SESSION["uid"];
            $result = $this->db->preparedQuery($query, $bind);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $userData = $rows[0];
                $this->id = $userData["id"];
                $this->username = $userData["username"];
                $this->name = $userData["name"];
                $this->typeTitle = $userData["typeTitle"];
                return true;
            }
        }
        return false;
    }

    public function login() {
        $query = "select users.*, user_types.title as typeTitle from users left join user_types on users.type=user_types.id where username=:username and password=:password";
        $bind[":username"] = $this->username;
        $bind[":password"] = $this->password;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $userData = $rows[0];
            $_SESSION["uid"] = $userData["id"];

            $this->id = $userData["id"];
            $this->name = $userData["name"];
            $this->typeTitle = $userData["typeTitle"];
            $this->type = $userData["type"];

            return true;
        } else {
            return false;
        }
    }

    public function updatePassword(UpdatePasswordValidator $validator) {
        if (!$validator->validateUpdatePassword($this))
            return false;

        try {
            $fields["password"] = $this->password;

            $condition["id"] = $this->id;
            $this->db->update("users", $fields, $condition);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function disconnect() {
        session_unset();
        session_destroy();
    }

    public function readAll() {
        return $this->db->query("select users.name, users.username, users.type, user_types.title as type from users"
                        . " join user_types on user_types.id=users.type");
    }

    public function readAllUserTypes() {
        $permissions = $this->db->query("select * from user_permissions")->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->db->query("select * from user_types");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $arr = $this->permissionsToArray($row["permissions"], $permissions);
            $row["permissions"] = array();
            foreach ($arr as $id) {
                $permission = $this->getPermissionById($permissions, $id);
                $row["permissions"][] = $permission["action"] . " " . $permission["subject"];
            }
            $userTypes["userTypes"][] = $row;
        }
        return $userTypes;
    }

    private function permissionsToArray($str, $permissions) {
        $arr = array();
        if ($str == "all") {
            foreach ($permissions as $permission) {
                $arr[] = $permission["id"];
            }
        } else {
            $arr = explode(",", $str);
        }
        return $arr;
    }

    private function getPermissionById($permissions, $id) {
        foreach ($permissions as $permission) {
            if ($permission["id"] == $id)
                return $permission;
        }
    }

    public function readAllPermissionsForDisplay() {
        $permissions = $this->db->query("select * from user_permissions")->fetchAll(PDO::FETCH_ASSOC);
        $userTypes = $this->db->query("select * from user_types")->fetchAll(PDO::FETCH_ASSOC);
        $output = array();
        foreach ($userTypes as $key => $userType) {
            $userTypes[$key]["permissionsArr"] = $this->permissionsToArray($userType["permissions"], $permissions);
            $output["userTypes"][] = $userType["title"];
        }
        foreach ($permissions as $permission) {
            $row = array();
            $row["description"] = $permission["action"] . " " . $permission["subject"];
            foreach ($userTypes as $userType) {
                $row["typeAllowed"][] = in_array($permission["id"], $userType["permissionsArr"]);
            }
            $output["permissions"][] = $row;
        }
        return $output;
    }

    public function create(CreateUserValidator $validator) {
        if (!$validator->validateCreate($this))
            return false;

        try {
            $fields["name"] = $this->name;
            $fields["username"] = $this->username;
            $fields["password"] = $this->password;
            $fields["type"] = $this->type;
            $this->db->insert("users", $fields);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}
