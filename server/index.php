<?php

include "globalFunctions.php";

$pageActions["user.php"] = array("login", "fetchLoggedUser", "disconnect", "readAllUsers", "updatePassword", "readAllUserTypes", "createUser", "readUser", "updateUser", "deleteUser", "userExists");
$pageActions["book.php"] = array("createBook", "deleteBook", "bookExists", "readBook", "readAllBooks", "readBooksNum", "updateBook", "readAllBooksForBorrow");
$pageActions["author.php"] = array("readAllAuthors");
$pageActions["borrow.php"] = array("readBorrowsByReader", "readBorrowsByReaderForDisplay", "readAllBorrowsByReader", "borrowReturnBooks", "readAllBorrowsByBook");
$pageActions["publisher.php"] = array("readAllPublishers");
$pageActions["reader.php"] = array("createReader", "readReader", "readReaderTypes", "readAllReaders", "updateReader", "readerExists", "deleteReader");
$pageActions["section.php"] = array("readAllSections");
$pageActions["action.php"] = array("readActionsByBook", "readActionsByReader", "readActionsByUser");

$param = new Param();
$action = $param->get("action");
if ($action == "login") {
    include getIncludePath($action);
    login();
} else if ($action == "fetchLoggedUser") {
    include getIncludePath($action);
    fetchLoggedUser();
} else {
    include "userPermission.php";
    include getIncludePath($action);
    $action();
}

function getIncludePath($action) {
    global $pageActions;
    foreach ($pageActions as $path => $actions) {
        foreach ($actions as $title) {
            if ($title == $action) {
                return $path;
            }
        }
    }
    throw new Exception();
}
