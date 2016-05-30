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
    public $hierarchy;
    public $permissionsArr;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function authenticate() {
        return isset($_SESSION["uid"]);
    }

    public function fetchLoggedUser() {
        if ($this->authenticate()) {
            $query = "select users.*, user_types.title as typeTitle, user_types.hierarchy, user_types.permissions from users left join user_types on users.type=user_types.id where users.id=:uid";
            $bind[":uid"] = $_SESSION["uid"];
            $result = $this->db->preparedQuery($query, $bind);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $userData = $rows[0];
                $this->id = $userData["id"];
                $this->username = $userData["username"];
                $this->name = $userData["name"];
                $this->typeTitle = $userData["typeTitle"];
                $this->type = $userData["type"];
                $this->hierarchy = $userData["hierarchy"];
                $this->permissionsArr = $this->permissionsToArray($userData["permissions"], $this->readAllPermissions());

                return true;
            }
        }
        return false;
    }

    public function login() {
        $query = "select users.*, user_types.title as typeTitle, user_types.hierarchy, user_types.permissions from users left join user_types on users.type=user_types.id where username=:username and password=:password";
        $bind[":username"] = $this->username;
        $bind[":password"] = $this->password;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $userData = $rows[0];
            $_SESSION["uid"] = $userData["id"];

            $this->id = $userData["id"];
            $this->username = $userData["username"];
            $this->name = $userData["name"];
            $this->typeTitle = $userData["typeTitle"];
            $this->type = $userData["type"];
            $this->hierarchy = $userData["hierarchy"];
            $this->permissionsArr = $this->permissionsToArray($userData["permissions"], $this->readAllPermissions());


            return true;
        } else {
            return false;
        }
    }

    public function updatePassword(UpdatePasswordValidator $validator) {
        if (!$validator->validate($this))
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
        return $this->db->query("select users.id, users.name, users.username, users.type, user_types.title as type from users"
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

    private function readAllPermissions() {
        return $this->db->query("select * from user_permissions")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAllPermissionsForDisplay() {
        $permissions = readAllPermissions();
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

    public function create(CreateUserValidator $validator, $loggedUserHierarchy) {
        if (!$validator->validate($this, $loggedUserHierarchy))
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

    public function readOne() {
        $query = "SELECT users.username, users.name, users.type FROM users WHERE id=:id";
        $bind[":id"] = $this->id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $user = $rows[0];
            $this->username = $user["username"];
            $this->name = $user["name"];
            $this->type = $user["type"];

            return true;
        }
        return false;
    }

    public function readByUsername() {
        $query = "SELECT users.id FROM users WHERE username=:username";
        $bind[":username"] = $this->username;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $user = $rows[0];
            $this->id = $user["id"];

            return true;
        }
        return false;
    }

    public function update(UpdateUserValidator $validator, $loggedUserHierarchy) {
        if (!$validator->validate($this, $loggedUserHierarchy))
            return false;

        try {
            $fields["name"] = $this->name;
            $fields["type"] = $this->type;

            $condition["id"] = $this->id;

            $this->db->update("users", $fields, $condition);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function delete(UserValidator $validator, $loggedUserHierarchy) {
        if (!$validator->validateDelete($this, $loggedUserHierarchy))
            return false;

        try {
            $condition["id"] = $this->id;
            $this->db->delete("users", $condition);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function enforcePermission($id) {
        if (!in_array($id, $this->permissionsArr)) {
            trigger_error("User doesn't have sufficient permissions for this action", E_USER_ERROR);
        }
    }

}
