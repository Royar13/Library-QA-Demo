<?php

include "globalFunctions.php";

$pageActions["user.php"] = array("login", "fetchLoggedUser", "disconnect");
$pageActions["book.php"] = array("createBook", "deleteBook", "bookExists", "readBook", "readAllBooks", "readBooksNum", "updateBook", "readAllBooksForBorrow");
$pageActions["author.php"] = array("readAllAuthors");
$pageActions["borrow.php"] = array("readBorrowsByReader", "readBorrowsByReaderForDisplay", "readAllBorrowsByReader", "borrowReturnBooks");
$pageActions["publisher.php"] = array("readAllPublishers");
$pageActions["reader.php"] = array("createReader", "readReader", "readReaderTypes", "readAllReaders", "updateReader", "readerExists");
$pageActions["section.php"] = array("readAllSections");

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
    switch ($action) {
        case "disconnect":
            disconnect();
            break;
        case "createBook":
            createBook();
            break;
        case "bookExists":
            bookExists();
            break;
        case "readBook":
            readBook();
            break;
        case "readAllBooks":
            readAllBooks();
            break;
        case "readBooksNum":
            readBooksNum();
            break;
        case "updateBook":
            updateBook();
            break;
        case "deleteBook":
            deleteBook();
            break;
        case "readAllBooksForBorrow":
            readAllBooksForBorrow();
            break;
        case "readAllAuthors":
            readAllAuthors();
            break;
        case "readBorrowsByReader":
            readBorrowsByReader();
            break;
        case "readBorrowsByReaderForDisplay":
            readBorrowsByReaderForDisplay();
            break;
        case "readAllBorrowsByReader":
            readAllBorrowsByReader();
            break;
        case "borrowReturnBooks":
            borrowReturnBooks();
            break;
        case "readAllPublishers":
            readAllPublishers();
            break;
        case "createReader":
            createReader();
            break;
        case "readReader":
            readReader();
            break;
        case "readReaderTypes":
            readReaderTypes();
            break;
        case "readAllReaders":
            readAllReaders();
            break;
        case "updateReader":
            updateReader();
            break;
        case "readerExists":
            readerExists();
            break;
        case "readAllSections":
            readAllSections();
            break;
    }
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
