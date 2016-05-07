<?php

//session_set_cookie_params(3600 * 24 * 300);
session_start();
spl_autoload_register("myAutoLoader");


function myAutoLoader($className) {
    $fileName = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.class.php';
    if (!file_exists($fileName))
        return false;
    include_once $fileName;
    if (method_exists($className, '__initStatic')) {
        $className::__initStatic();
    }
}

$GLOBALS["conn"] = mysqli_connect("localhost", "root", "", "atm-v1");
mysqli_query($GLOBALS["conn"], "SET NAMES 'utf8'");
?>