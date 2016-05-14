<?php

include "globalFunctions.php";
include "userPermission.php";

$user = Factory::getUser();
$user->disconnect();
