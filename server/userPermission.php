<?php

$user = Factory::getUser();
$user->fetchLoggedUser();
if (!$user->authenticate()) {
    trigger_error("Not logged in to user", E_USER_ERROR);
}

function enforcePermission($id) {
    $user = Factory::getUser();
    $user->enforcePermission($id);
}