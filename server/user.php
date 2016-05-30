<?php

function login() {
    $param = new Param();
    $user = Factory::getUser();

    $user->username = $param->get("username");
    $user->password = $param->get("password");
    if ($user->login()) {
        $output["success"] = true;
        $output["username"] = $user->username;
        $output["name"] = $user->name;
        $output["type"] = $user->type;
        $output["typeTitle"] = $user->typeTitle;
        $output["permissionsArr"] = $user->permissionsArr;
    } else {
        $output["success"] = false;
        $output["errors"]["general"][] = "פרטי התחברות שגויים";
    }
    Factory::write($output);
}

function fetchLoggedUser() {
    $param = new Param();
    $user = Factory::getUser();
    if ($user->fetchLoggedUser()) {
        $output["success"] = true;
        $output["username"] = $user->username;
        $output["name"] = $user->name;
        $output["type"] = $user->type;
        $output["typeTitle"] = $user->typeTitle;
        $output["permissionsArr"] = $user->permissionsArr;
    } else {
        $output["success"] = false;
    }
    Factory::write($output);
}

function disconnect() {
    $user = Factory::getUser();
    $user->disconnect();
}

function readAllUsers() {
    enforcePermission(10);

    $user = Factory::getUser();
    $result = $user->readAll();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["users"][] = $row;
    }
    Factory::write($output);
}

function updatePassword() {
    $user = Factory::getUser();
    $param = new Param();
    $user->currentPassword = $param->get("currentPassword");
    $user->password = $param->get("password");
    $user->passwordRepeat = $param->get("passwordRepeat");
    $validator = Factory::makeValidator("UpdatePassword");

    if ($user->updatePassword($validator)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function readAllUserTypes() {
    $user = Factory::getUser();
    $output = $user->readAllUserTypes();
    Factory::write($output);
}

function createUser() {
    enforcePermission(11);

    $loggedUser = Factory::getUser();
    $user = Factory::makeUser();
    $param = new Param();
    $user->username = $param->get("username");
    $user->name = $param->get("name");
    $user->password = $param->get("password");
    $user->passwordRepeat = $param->get("passwordRepeat");
    $user->type = $param->get("type");

    $validator = Factory::makeValidator("CreateUser");

    if ($user->create($validator, $loggedUser->hierarchy)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function updateUser() {
    enforcePermission(12);

    $loggedUser = Factory::getUser();
    $user = Factory::makeUser();
    $param = new Param();
    $user->id = $param->get("id");
    $user->name = $param->get("name");
    $user->type = $param->get("type");
    $validator = Factory::makeValidator("UpdateUser");

    if ($user->update($validator, $loggedUser->hierarchy)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function readUser() {
    enforcePermission(10);

    $user = Factory::makeUser();
    $param = new Param();
    $user->id = $param->get("id");
    $user->readOne();
    $output["name"] = $user->name;
    $output["username"] = $user->username;
    $output["type"] = $user->type;

    Factory::write($output);
}

function deleteUser() {
    enforcePermission(13);

    $loggedUser = Factory::getUser();
    $user = Factory::makeUser();
    $param = new Param();
    $user->id = $param->get("id");
    $validator = Factory::makeValidator("User");
    if ($user->delete($validator, $loggedUser->hierarchy)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function userExists() {
    enforcePermission(10);

    $user = Factory::makeUser();
    $param = new Param();
    $user->username = $param->get("username");
    if ($user->readByUsername()) {
        $output["success"] = true;
        $output["id"] = $user->id;
    } else {
        $output["success"] = false;
        $output["errors"]["general"][] = "לא נמצא המשתמש המבוקש";
    }
    Factory::write($output);
}

function readAllPermissionsForDisplay() {
    
}
