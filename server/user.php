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
    $user = Factory::getUser();
    $result = $user->readAll();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["users"][] = $row;
    }
    Factory::write($output);
}

function updatePassword() {
    $user = Factory::getUser();
    $user->fetchLoggedUser();
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

function readAllPermissionsForDisplay() {
    
}

function createUser() {
    $user = Factory::getUser();
    $param = new Param();
    $user->username = $param->get("username");
    $user->name = $param->get("name");
    $user->password = $param->get("password");
    $user->passwordRepeat = $param->get("passwordRepeat");
    $user->type = $param->get("type");

    $validator = Factory::makeValidator("CreateUser");

    if ($user->create($validator)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}

function updateUser() {
    $user = Factory::getUser();
    $param = new Param();
    $user->name = $param->get("name");
    $user->type = $param->get("type");
    $validator = Factory::makeValidator("UpdateUser");

    if ($user->create($validator)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}
