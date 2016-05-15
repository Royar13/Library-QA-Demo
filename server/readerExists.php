<?php

include "globalFunctions.php";
include "userPermission.php";

$reader = Factory::makeReader();
$param = new Param();
$reader->id = $param->get("id");
$errorLogger = new ErrorLogger();
$result = $reader->validateIDExist($errorLogger);
if ($errorLogger->isValid()) {
    $output["success"] = true;
} else {
    $output = $errorLogger->getErrors();
}
Factory::write($output);
